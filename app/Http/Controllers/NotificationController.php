<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        return Inertia::render(
            'Notifications/Index',
            [
                'notifications' => $notifications,
            ]
        );
    }

    public function read(
        Notification $notification
    ) {
        if (
            $notification->user_id === auth()->id()
        ) {
            $notification->update([
                'is_read' => true,
            ]);
        }

        return back();
    }

    public function readAll()
    {
        Notification::where(
            'user_id',
            auth()->id()
        )->update([
            'is_read' => true,
        ]);

        return back();
    }
}