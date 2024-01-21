<?php

namespace App\Http\Controllers;

use App\Models\FileImport;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(FileImport $fileImport, Request $request)
    {
        $pageSize = $request->query("pageSize",10);
        $scheduledFor = $request->query("scheduledFor");
        $status = $request->query("status");

        return Notification::where("file_import_id", $fileImport->id)
            ->when($scheduledFor, function ($query) use ($scheduledFor) {
                $query->where("scheduled_for", $scheduledFor);
            })
            ->when($status && $status != 'ALL', function ($query) use ($status) {
                $query->where("status", $status);
            })
            ->with('contact')
            ->paginate($pageSize);
    }
}
