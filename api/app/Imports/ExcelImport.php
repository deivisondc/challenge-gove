<?php

namespace App\Imports;

use App\Enums\FileImportCellError;
use App\Enums\FileImportStatus;
use App\Models\Contact;
use App\Models\Notification;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Enums\NotificationStatus;
use App\Models\FileImport;
use App\Models\FileImportError;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class ExcelImport extends StringValueBinder
    implements ToArray, WithHeadingRow, WithChunkReading, ShouldQueue, WithEvents, SkipsEmptyRows
{
    use Importable, RemembersChunkOffset;

    public function __construct(
        protected FileImport $fileImport
    ) {}

    public function array(array $rows)
    {
        if (count($rows) == 0) {
            $this->setStatusOnFileImport(FileImportStatus::ERROR);
        }

        if ($this->fileImport->status == FileImportStatus::QUEUED->name) {
            $this->setStatusOnFileImport(FileImportStatus::PROCESSING);
        }

        $savedContacts = [];

        for ($i=0; $i < count($rows); $i++) {
            $row = $rows[$i];

            $rowName = array_key_exists('name', $row) ? $row['name'] : $row[0];
            $rowContact = array_key_exists('contact', $row) ? $row['contact'] : $row[1];
            $rowScheduledFor = array_key_exists('scheduled_for', $row) ? $row['scheduled_for'] : $row[2];

            $rowValidationData = $this->validateRowOrFail($rowName, $rowContact, $rowScheduledFor);

            if ($rowValidationData['success']) {
                $contact = $savedContacts[$rowContact] ?? Contact::where('contact', $rowContact)->first();

                if (!$contact) {
                    $contact = new Contact();
                    $contact->name = $rowName;
                    $contact->contact = $rowContact;
                    $contact->save();

                    $savedContacts[$contact->contact] = $contact;
                }

                $dateString = $rowScheduledFor;
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
                    $fileImportError->error = 'Row ' . $rowNumber . ': ' . $error;
                    $fileImportError->save();
                }
            }
        }
    }

    public function chunkSize(): int
    {
        return 10000;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function() {
                $this->setStatusOnFileImport(FileImportStatus::ERROR);
            },
        ];
    }

    private function setStatusOnFileImport(FileImportStatus $status) {
        $this->fileImport->status = $status->name;
        $this->fileImport->save();
    }

    private function validateRowOrFail($rowName, $rowContact, $rowScheduledFor): array {
        $result = [ 'success' => true ];

        if (empty($rowName)) {
            $result['success'] = false;
            $result['errors'][] = FileImportCellError::MISSING_NAME->value;
        }
        if (empty($rowContact)) {
            $result['success'] = false;
            $result['errors'][] = FileImportCellError::MISSING_CONTACT->value;
        }
        if (empty($rowScheduledFor)) {
            $result['success'] = false;
            $result['errors'][] = FileImportCellError::MISSING_DATE->value;
        }

        return $result;
    }
}
