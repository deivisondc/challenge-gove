<?php

namespace App\Repositories\Interfaces;
use App\DTO\SaveFileImportDTO;
use App\DTO\UpdateFileImportDTO;
use App\Models\FileImport;
use stdClass;

interface FileImportRepositoryInterface
{
    public function filter(array $filter): array;
    public function save(SaveFileImportDTO $dto): FileImport;
    public function update(UpdateFileImportDTO $dto): FileImport;

}
