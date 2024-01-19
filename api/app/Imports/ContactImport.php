<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Enums\NotificationStatus;
use App\Models\FileImport;
use DateTime;
use Illuminate\Support\Facades\DB;

class ContactImport implements ToArray, WithHeadingRow
{

    public function __construct(
        protected FileImport $fileImport
    ) {}

    public function array(array $rows)
    {
        $savedContacts = [];

        foreach ($rows as $row)
        {
            if (!empty($row['name'])) {
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
            }
        }
    }
}
