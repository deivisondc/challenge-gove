<?php

namespace App\Jobs;

use App\Enums\NotificationStatus;
use App\Models\Notification;
use App\Services\NotificationService;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Notification $notification)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        if (empty($this->notification)) {
            return;
        }

        $scheduledFor = DateTime::createFromFormat('Y-m-d', $this->notification->scheduled_for);
        if ($scheduledFor > new DateTime()) {
            return;
        }

        $notificationService->updateStatus($this->notification, NotificationStatus::QUEUED);

        try {
            $notificationService->send($this->notification->contact, 'MOCK MESSAGE');
            $notificationService->updateStatus($this->notification, NotificationStatus::SUCCESS);
        } catch (\Exception $e) {
            $notificationService->updateStatus($this->notification, NotificationStatus::ERROR);
        }

    }
}
