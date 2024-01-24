<?php

namespace App\Repositories\Interfaces;
use App\Models\FileImportError;

interface FileImportErrorRepositoryInterface
{
    public function filter(array $filter): array;

}
