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
                'product_photo' => $notification->data['product_photo'] ?? null,
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
                // 'user_photo' => $notification->data['user_photo'] ?? null,
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

        // Return the formatted notifications
        return response()->json($formattedNotifications);
    }


    public function reviewNotifications()
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;

        // Retrieve all notifications of type ReviewNotification
        $notifications = $vendor->notifications()
            ->where('type', 'App\Notifications\ReviewNotification')
            ->orderBy('created_at', 'desc')
            ->get();

        
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'data' => $notification->data,
                // 'user_photo' => $notification->data['user_photo'] ?? null,
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

      
        return response()->json($formattedNotifications);
    }

    public function stockNotifications()
    {
    
        $vendor = Auth::user()->vendor;

        // Retrieve stock notifications
        $notifications = $vendor->notifications()
            ->where('type', 'App\Notifications\ProductStockNotification')
            ->orderBy('created_at', 'desc')
            ->get();

        // Format the notifications
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'product_id' => $notification->data['product_id'],
                'product_name' => $notification->data['product_name'],
                'product_photo' => $notification->data['product_photo'],
                'remaining_stock' => $notification->data['remaining_stock'],
                'mini_stock' => $notification->data['mini_stock'],
                'message' => $notification->data['message'],
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

        // Return the formatted notifications
        return response()->json($formattedNotifications);
    }



    public function markAsRead($id)
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;

        $notification = $vendor->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }

        // Mark the notification as read
        $notification->markAsRead();

        return response()->json([
            'id' => $notification->id,
            'data' => $notification->data,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at->toDateTimeString(),
            'message' => 'Notification marked as read successfully.'
        ]);
    }
}
