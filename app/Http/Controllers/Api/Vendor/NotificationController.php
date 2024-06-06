<?php

namespace App\Http\Controllers\Api\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;

        $notifications = $vendor->notifications()->orderBy('created_at', 'desc')->get();

        return response()->json($notifications);
    }


    public function markAsRead(Request $request)
    {
     
        $vendor = Auth::user()->vendor;
        $vendor->unreadNotifications->markAsRead();

        return response()->json(['message' => 'Notifications marked as read']);
    }
}
