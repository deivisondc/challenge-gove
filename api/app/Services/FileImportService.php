<?php

namespace App\Services;

use App\DTO\SaveFileImportDTO;
use App\DTO\UpdateFileImportDTO;
use App\Http\Utils;
use App\Imports\ExcelImport;
use App\Jobs\UpdateFileImportStatus;
use App\Repositories\Interfaces\FileImportRepositoryInterface;
use DateTime;

class FileImportService
{
    public function __construct(
        protected FileImportRepositoryInterface $repository,
    ) {}

    public function getAll(array $filter): array
    {
        $fileImports = $this->repository->filter($filter);

        $links = Utils::formatPaginationLinks($fileImports);
        $fileImports['links'] = $links;

        return $fileImports;
    }

    public function importFile(\Illuminate\Http\UploadedFile $file)
    {
        $filename = $file->getClientOriginalName();

        # Local (public)
        // $filename = 'Teste.xlsx';

        $saveFileImportDTO = SaveFileImportDTO::make($filename);
        $fileImport = $this->repository->save($saveFileImportDTO);

        (new ExcelImport($fileImport, $this))->queue($file)->chain([
            new UpdateFileImportStatus((object) $fileImport, $this),
        ]);

        return $fileImport->toArray();
    }

    public function updateStatus(UpdateFileImportDTO $dto)
    {
        return $this->repository->update($dto);
    }
}
