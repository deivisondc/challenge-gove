<?php

namespace App\Enums;

enum FileImportCellError: string
{
    case MISSING_NAME = "Missing value for 'name' column.";
    case MISSING_CONTACT = "Missing value for 'contact' column.";
    case MISSING_DATE = "Missing value for 'scheduled for' column.";
}
