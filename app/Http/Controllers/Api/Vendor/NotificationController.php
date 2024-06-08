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

        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'data' => $notification->data,
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

        return response()->json($formattedNotifications);
    }


    public function orderNotifications()
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;

        // Retrieve order notifications
        $notifications = $vendor->notifications()
            ->where('type', 'App\Notifications\VendorOrderNotification')
            ->orderBy('created_at', 'desc')
            ->get();

        // Format the notifications
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'product' => $notification->data['product'],
                'quantity' => $notification->data['quantity'],
                'order_number' => $notification->data['order_number'],
                'shipping_full_name' => $notification->data['shipping_full_name'],
                'message' => $notification->data['message'],
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

        // Return the formatted notifications
        return response()->json($formattedNotifications);
    }


    public function followNotifications()
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;

        // Retrieve follow notifications
        $notifications = $vendor->notifications()
            ->where('type', 'App\Notifications\VendorFollowedNotification')
            ->orderBy('created_at', 'desc')
            ->get();

        // Format the notifications
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'message' => $notification->data['message'],
                'followers_count' => $notification->data['followers_count'],
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

        // Return the formatted notifications
        return response()->json($formattedNotifications);
    }


    public function markAsRead(Request $request)
    {
     
        $vendor = Auth::user()->vendor;
        $vendor->unreadNotifications->markAsRead();

        return response()->json(['message' => 'Notifications marked as read']);
    }
}
