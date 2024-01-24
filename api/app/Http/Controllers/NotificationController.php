<?php

namespace App\Http\Controllers;

use App\DTO\UpdateNotificationDTO;
use App\Enums\NotificationStatus;
use App\Http\Utils;
use App\Jobs\UpdateNotification;
use App\Models\FileImport;
use App\Models\Notification;
use App\Services\NotificationService;
use DateTime;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $service
    ) {}

    public function index(FileImport $fileImport, Request $request)
    {
        $notifications = $this->service->getByFile($fileImport->id, $request->query->all());

        return response()->json($notifications);
    }

    public function retry(Notification $notification)
    {
        $notification = $this->service->updateStatus($notification, NotificationStatus::IDLE);
        $this->service->dispatchJobForOneNotification($notification);

        return response()->json($notification);
    }

    public function updateScheduledFor(Notification $notification, Request $request)
    {
        $scheduled_for = $request->json()->get('scheduled_for');
        $formattedDate = Utils::parseDate($scheduled_for);

        $fieldsToUpdate = UpdateNotificationDTO::make($formattedDate->format('Y-m-d'), NotificationStatus::IDLE);
        $notification = $this->service->update($notification, $fieldsToUpdate);

        return response()->json($notification);
    }

    public function cancel(Notification $notification)
    {
        $notification = $this->service->updateStatus($notification, NotificationStatus::CANCELED);

        return response()->json($notification);
    }
}
