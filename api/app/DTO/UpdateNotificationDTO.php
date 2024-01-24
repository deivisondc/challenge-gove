<?php

namespace App\DTO;

use App\Enums\NotificationStatus;

class UpdateNotificationDTO {
    public function __construct(
        public string $scheduled_for,
        public string $status,
    ) {}


    public static function make(string $scheduledFor, NotificationStatus $status): self {
        return new self(
            $scheduledFor,
            $status->name,
        );
    }
}
