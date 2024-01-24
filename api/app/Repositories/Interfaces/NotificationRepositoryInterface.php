<?php

namespace App\Repositories\Interfaces;

use App\DTO\UpdateNotificationDTO;
use App\Enums\NotificationStatus;
use App\Models\Notification;

interface NotificationRepositoryInterface
{
    public function filter(array $filter): array;
    public function allUntilToday(NotificationStatus $status);
    public function updateStatus(Notification $notification, NotificationStatus $status);
    public function updateStatusInBatch(array $notifications);
    public function update(Notification $notification, UpdateNotificationDTO $fieldsToUpdate);

}
