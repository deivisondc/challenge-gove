<?php

namespace App\Enums;

enum FileImportStatus
{
    case QUEUED;
    case PROCESSING;
    case SUCCESS;
    case WARNING;
    case ERROR;
}
