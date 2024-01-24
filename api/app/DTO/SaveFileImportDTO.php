<?php

namespace App\DTO;

use App\Enums\FileImportStatus;

class SaveFileImportDTO {

    public function __construct(
        public string $filename,
        public string $status,
    ) {}


    public static function make(string $filename, FileImportStatus $status = FileImportStatus::QUEUED): self {
        return new self(
            $filename,
            $status->name,
        );
    }
}
