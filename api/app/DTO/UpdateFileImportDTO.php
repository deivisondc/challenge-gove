<?php

namespace App\DTO;

use App\Enums\FileImportStatus;

class UpdateFileImportDTO {

    public function __construct(
        public int $id,
        public string $filename,
        public string $status,
    ) {}


    public static function make(int $id, string $filename, FileImportStatus $status): self {
        return new self(
            $id,
            $filename,
            $status->name,
        );
    }
}
