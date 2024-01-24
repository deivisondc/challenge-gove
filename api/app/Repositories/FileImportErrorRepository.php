<?php

namespace App\Repositories;

use App\Models\FileImport;
use App\Models\FileImportError;
use App\Repositories\Interfaces\FileImportErrorRepositoryInterface;

class FileImportErrorRepository implements FileImportErrorRepositoryInterface
{
    public function __construct(
        protected FileImport $model,
    ) {}

    public function filter(array $filter): array
    {
        $pageSize = $filter["pageSize"] ?? 10;
        $fileImportId = $filter["fileImportId"];

        $fileImportErrors = FileImportError::where("file_import_id", $fileImportId)
            ->paginate($pageSize)
            ->toArray();

        return $fileImportErrors;
    }
}
