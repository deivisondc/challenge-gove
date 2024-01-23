<?php

namespace App\Jobs;

use App\Enums\NotificationStatus;
use App\Models\Notification;
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
        $notifications = Notification::where([
            ['scheduled_for', '<=', now()->format('Y-m-d')],
            ['status', '=', NotificationStatus::IDLE->name]
        ]);

        if (empty($notifications)) {
            return;
        }

        $notifications->update(['status' => NotificationStatus::QUEUED->name]);

        $notifications = Notification::where([
            ['scheduled_for', '<=', now()->format('Y-m-d')],
            ['status', '=', NotificationStatus::QUEUED->name]
        ])->get();

        foreach ($notifications as $notification) {
            try {
                $notificationService->send($notification->contact, 'MOCK MESSAGE');

                $notification->status = NotificationStatus::SUCCESS->name;
            } catch (\Exception $e) {
                $notification->status = NotificationStatus::ERROR->name;
            } finally {
                $notification->save();
            }
        }

    }
}
