<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Notifications marked as read.');
    }
}
