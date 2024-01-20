<?php

namespace App\Imports;

use App\Enums\FileImportCellError;
use App\Enums\FileImportStatus;
use App\Models\Contact;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Enums\NotificationStatus;
use App\Models\FileImport;
use App\Models\FileImportError;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class ContactImport extends StringValueBinder implements ToArray, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use RemembersChunkOffset;

    public function __construct(
        protected FileImport $fileImport
    ) {}

    public function array(array $rows)
    {
        if ($this->fileImport->status == FileImportStatus::QUEUED->name) {
            $this->fileImport->status = FileImportStatus::PROCESSING->name;
            $this->fileImport->save();
        }

        $savedContacts = [];

        for ($i=0; $i < count($rows); $i++) {
            $row = $rows[$i];

            $rowValidationData = $this->validateRowOrFail($row);

            if ($rowValidationData['success']) {
                $contact = $savedContacts[$row['contact']] ?? Contact::where('contact', $row['contact'])->first();

                if (!$contact) {
                    $contact = new Contact();
                    $contact->name = $row['name'];
                    $contact->contact = $row['contact'];
                    $contact->save();

                    $savedContacts[$contact->contact] = $contact;
                }

                $dateString = $row['scheduled_for'];
                $dateObject = DateTime::createFromFormat('Y-m-d', $dateString);

                $notification = new Notification();
                $notification->contact()->associate($contact);
                $notification->fileImport()->associate($this->fileImport);
                $notification->scheduled_for = $dateObject->format('Y-m-d');
                $notification->status = NotificationStatus::IDLE->name;
                $notification->save();
            } else {
                $rowNumber = $this->getChunkOffset() + $i;

                foreach ($rowValidationData['errors'] as $error) {
                    $fileImportError = new FileImportError();
                    $fileImportError->fileImport()->associate($this->fileImport);
                    $fileImportError->error = 'Line ' . $rowNumber . ': ' . $error;
                    $fileImportError->save();
                }
    }
        }
    }

    public function chunkSize(): int
    {
        return 10000;
    }

    private function validateRowOrFail($row): array {
        $result = [ 'success' => true ];

        if (empty($row['name'])) {
            $result['success'] = false;
            $result['errors'][] = FileImportCellError::MISSING_NAME->value;
        }
        if (empty($row['contact'])) {
            $result['success'] = false;
            $result['errors'][] = FileImportCellError::MISSING_CONTACT->value;
        }
        if (empty($row['scheduled_for'])) {
            $result['success'] = false;
            $result['errors'][] = FileImportCellError::MISSING_DATE->value;
        }

        return $result;
    }
}
