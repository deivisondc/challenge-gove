<?php

namespace App\Enums;

enum NotificationStatus
{
    case IDLE;
    case QUEUED;
    case SUCCESS;
    case ERROR;
}
