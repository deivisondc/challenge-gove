<?php

namespace App\Jobs;

use App\Enums\FileImportStatus;
use App\Models\FileImport;
use App\Models\FileImportError;
use App\Models\Notification;
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
        private FileImport $fileImport
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

        if ($countFileImportErrors > 0) {
            $this->fileImport->status = FileImportStatus::WARNING->name;
        } else {
            $this->fileImport->status = FileImportStatus::SUCCESS->name;
        }

        $this->fileImport->save();

        SendNotification::dispatch()->onQueue('notifications');
    }

    private function hasErrors($fileImport): bool
    {
        return $fileImport->status == FileImportStatus::ERROR->name;
    }
}
