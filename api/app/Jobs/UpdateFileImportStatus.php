<?php

namespace App\Jobs;

use App\DTO\UpdateFileImportDTO;
use App\Enums\FileImportStatus;
use App\Models\FileImport;
use App\Services\FileImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateFileImportStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected FileImport $fileImport,
        protected FileImportService $service,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->hasErrors($this->fileImport)) {
            return;
        }

        $countFileImportErrors = $this->fileImport->fileImportErrors->count();

        $status = '';

        if ($countFileImportErrors > 0) {
            $status = FileImportStatus::WARNING;
        } else {
            $status = FileImportStatus::SUCCESS;
        }

        $dto = UpdateFileImportDTO::make(
            $this->fileImport->id,
            $this->fileImport->filename,
            $status
        );
        $this->service->updateStatus($dto);

        SendNotification::dispatch()->onQueue('notifications');
    }

    private function hasErrors($fileImport): bool
    {
        return $fileImport->status == FileImportStatus::ERROR->name;
    }
}
