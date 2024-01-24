<?php

namespace App\Services;

use App\Http\Utils;
use App\Repositories\Interfaces\FileImportErrorRepositoryInterface;

class FileImportErrorService
{
    public function __construct(
        protected FileImportErrorRepositoryInterface $repository,
    ) {}

    public function getByFile(int $fileImportId, array $filters): array
    {
        $filters["fileImportId"] = $fileImportId;
        $fileImportErrors = $this->repository->filter($filters);

        $links = Utils::formatPaginationLinks($fileImportErrors);
        $fileImportErrors['links'] = $links;

        return $fileImportErrors;
    }
}
