<?php

namespace App\Jobs;

use App\Enums\NotificationStatus;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        $notifications = $notificationService->getAllUntilToday(NotificationStatus::IDLE);

        if (empty($notifications)) {
            return;
        }

        $notifications->update(['status' => NotificationStatus::QUEUED->name]);

        $notifications = $notificationService->getAllUntilToday(NotificationStatus::QUEUED)
            ->chunkById(5000, function ($notificationsArray) use ($notificationService) {
                $notificationService->updateStatusInBatch($notificationsArray, NotificationStatus::SUCCESS);
            });
    }
}
