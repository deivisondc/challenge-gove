<?php

namespace App\Repositories;

use App\DTO\UpdateNotificationDTO;
use App\Enums\NotificationStatus;
use App\Models\FileImport;
use App\Models\FileImportError;
use App\Models\Notification;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function __construct(
        protected FileImport $model,
    ) {}

    public function filter(array $filter): array
    {
        $fileImportId = $filter["fileImportId"];
        $pageSize = $filter["pageSize"] ?? 10;
        $name = $filter["name"] ?? null;
        $contact = $filter["contact"] ?? null;
        $scheduledFor = $filter["scheduledFor"] ?? null;
        $status = $filter["status"] ?? null;

        $notifications = Notification::with('contact')
            ->where("file_import_id", $fileImportId)
            ->when($scheduledFor, function ($query) use ($scheduledFor) {
                $query->where("scheduled_for", $scheduledFor);
            })
            ->when($name, function ($query) use ($name) {
                $query->whereHas("contact", function ($query) use ($name) {
                    $query->where("name", "ilike", "%{$name}%");
                });
            })
            ->when($contact, function ($query) use ($contact) {
                $query->whereHas("contact", function ($query) use ($contact) {
                    $query->where("contact", "ilike", "%{$contact}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where("status", $status);
            })
            ->orderBy('id', 'desc')
            ->with('contact')
            ->paginate($pageSize)
            ->toArray();

        return $notifications;
    }

    public function allUntilToday(NotificationStatus $status)
    {
        $notifications = Notification::where([
            ['scheduled_for', '<=', now()->format('Y-m-d')],
            ['status', '=', $status->name]
        ]);

        return $notifications;
    }

    public function updateStatus(Notification $notification, NotificationStatus $status)
    {
        $notification->status = $status->name;
        $notification->save();

        return $notification;
    }

    public function updateStatusInBatch(array $notifications)
    {
        $this->model->upsert($notifications, ['id'], ['status']);
    }

    public function update(Notification $notification, UpdateNotificationDTO $fieldsToUpdate)
    {
        $notification->status = $fieldsToUpdate->status;
        $notification->scheduled_for = $fieldsToUpdate->scheduled_for;
        $notification->save();

        return $notification;
    }
}
