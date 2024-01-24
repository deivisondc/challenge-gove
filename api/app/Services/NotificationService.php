<?php

namespace App\Services;

use App\DTO\UpdateNotificationDTO;
use App\Enums\NotificationStatus;
use App\Http\Utils;
use App\Jobs\UpdateNotification;
use App\Models\Contact;
use App\Models\Notification;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class NotificationService
{
    public function __construct(
        protected NotificationRepositoryInterface $repository,
    ) {}

    public function getByFile(int $fileImportId, array $filters): array
    {
        $filters["fileImportId"] = $fileImportId;
        $fileImportErrors = $this->repository->filter($filters);

        $links = Utils::formatPaginationLinks($fileImportErrors);
        $fileImportErrors['links'] = $links;

        return $fileImportErrors;
    }

    public function getAllUntilToday(NotificationStatus $status)
    {
        $notifications = $this->repository->allUntilToday($status);
        return $notifications;
    }

    public function updateStatus(Notification $notification, NotificationStatus $status)
    {
        $notification = $this->repository->updateStatus($notification, $status);

        return $notification;
    }

    public function update(Notification $notification, UpdateNotificationDTO $fieldsToUpdate)
    {
        $notification = $this->repository->update($notification, $fieldsToUpdate);
        $this->dispatchJobForOneNotification($notification);

        return $notification;
    }

    public function dispatchJobForOneNotification(Notification $notification)
    {
        UpdateNotification::dispatch($notification)->onQueue('notifications');
    }

    public function send(Contact $contact, string $message)
    {
        // send notification
        return true;
    }

    public function updateStatusInBatch($notifications, NotificationStatus $status)
    {
        $notificationsToSave = [];

        foreach ($notifications as $notification) {
            $item = [
                "id" => $notification->id,
                "contact_id" => $notification->contact_id,
                "file_import_id" => $notification->file_import_id,
                "scheduled_for" => $notification->scheduled_for,
                "status" => $status
            ];

            try {
                $this->send($notification->contact, 'MOCK MESSAGE');
                $item['status'] = NotificationStatus::SUCCESS->name;
            } catch (\Exception $e) {
                $item['status'] = NotificationStatus::ERROR->name;
            } finally {
                $notificationsToSave[] = $item;
            }
        }

        $this->repository->updateStatusInBatch($notificationsToSave);
    }
}
