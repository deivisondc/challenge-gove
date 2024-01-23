<?php

namespace App\Http\Controllers;

use App\Http\Utils;
use App\Models\FileImport;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(FileImport $fileImport, Request $request)
    {
        $pageSize = $request->query("pageSize",10);
        $name = $request->query("name");
        $contact = $request->query("contact");
        $scheduledFor = $request->query("scheduledFor");
        $status = $request->query("status");

        $notifications = Notification::with('contact')
            ->where("file_import_id", $fileImport->id)
            ->when($scheduledFor, function ($query) use ($scheduledFor) {
                $query->where("scheduled_for", $scheduledFor);
            })
            ->when($name, function ($query) use ($name) {
                $query->whereHas("contact", function ($query) use ($name) {
                    $query->where("name", "ilike", "%{$name}%");
                });
            })
            ->when($contact, function ($query) use ($contact) {
                $query->whereHas("contact", function ($query) use ($contact) {
                    $query->where("contact", "ilike", "%{$contact}%");
                });
            })
            ->when($status && $status != 'ALL', function ($query) use ($status) {
                $query->where("status", $status);
            })
            ->orderBy('id', 'desc')
            ->with('contact')
            ->paginate($pageSize)
            ->toArray();

        $links = Utils::formatPaginationLinks($notifications['links'], $notifications['last_page'], $notifications['current_page']);
        $notifications['links'] = $links;

        return response()->json($notifications);
    }
    }
}
