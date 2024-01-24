<?php

namespace App\Repositories;

use App\DTO\SaveFileImportDTO;
use App\DTO\UpdateFileImportDTO;
use App\Models\FileImport;
use App\Repositories\Interfaces\FileImportRepositoryInterface;
use DateTime;
use stdClass;

class FileImportRepository implements FileImportRepositoryInterface
{
    public function __construct(
        protected FileImport $model,
    ) {}

    public function filter(array $filter): array
    {
        $pageSize = $filter["pageSize"] ?? 10;
        $createdAt = $filter["createdAt"] ?? 10;

        $fileImports = $this->model
            ->when($createdAt, function ($query) use ($createdAt) {
                $query->whereDate("created_at", $createdAt);
            })
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();

        return $fileImports;
    }

    public function save(SaveFileImportDTO $dto): FileImport
    {
        $fileImport = $this->model->create((array) $dto);
        return $fileImport;
    }

    public function update(UpdateFileImportDTO $dto): FileImport
    {
        $fileImport = $this->model->find($dto->id);
        $fileImport->update((array) $dto);

        return $fileImport;
    }
}
