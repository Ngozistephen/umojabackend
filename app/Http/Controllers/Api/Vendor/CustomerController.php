<?php

namespace App\Http\Controllers\Api\Vendor;

use DB;
use App\Models\User;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\VendorResource;
use App\Notifications\VendorFollowedNotification;
use App\Notifications\VendorUnfollowNotification;

class CustomerController extends Controller
{
    public function followVendor(Request $request, $vendorId)
    {
        $user = auth()->user();
        $vendor = Vendor::findOrFail($vendorId);
        
        if ($user->followingVendors()->where('vendor_id', $vendorId)->exists()) {
            return response()->json(['message' => 'Already following this vendor.'], 400);
        }

        $user->followingVendors()->attach($vendorId);
        $followersCount = $vendor->followers()->count();

      
        $vendor->notify(new VendorFollowedNotification($user, $followersCount));

        return response()->json(['message' => 'Vendor followed successfully.']);
    }



    public function unfollowVendor(Request $request, $vendorId)
    {
        $user = auth()->user();
        $vendor = Vendor::findOrFail($vendorId);

        if (!$user->followingVendors()->where('vendor_id', $vendorId)->exists()) {
            return response()->json(['message' => 'Not following this vendor.'], 400);
        }

        $user->followingVendors()->detach($vendorId);
        
        $vendor->notify(new VendorUnfollowNotification($user));

        return response()->json(['message' => 'Vendor unfollowed successfully.']);
    }

    public function getFollowingCount()
    {
        $user = auth()->user();
        return new UserResource($user);
    }

    public function getVendorFollowersCount($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        return new VendorResource($vendor);
    }

    public function getFollowedVendors(Request $request)
    {
        $user = auth()->user();
        $followedVendors = $user->followingVendors()->get();

        return VendorResource::collection($followedVendors);
        // return UserResource::collection($followedVendors);
    }


   


  




    public function getVendorFollowers($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);

        // Get all followers
        $followers = $vendor->followers()->get();

        // Total number of followers
        $totalFollowers = $followers->count();

       

        // Get users who have ordered from the vendor
        $orderUsers = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->where('order_product.vendor_id', $vendorId)
            ->distinct()
            ->pluck('orders.user_id')
            ->toArray();

        // Combine followers and order users
        $followerIds = $followers->pluck('id')->toArray();
        $allUserIds = array_unique(array_merge($followerIds, $orderUsers));

        // Retrieve all user data for the combined user ids
        $allUsers = User::whereIn('id', $allUserIds)->get();

        // Retrieve order details for users who have ordered from the vendor
        $orders = Order::whereIn('id', function ($query) use ($vendorId) {
            $query->select('order_id')
                ->from('order_product')
                ->where('vendor_id', $vendorId);
        })->whereIn('user_id', $allUserIds)->get()->groupBy('user_id');

        $userStatus = $allUsers->map(function ($user) use ($orders) {
            $orderDetails = $orders->get($user->id) ?? [];
            $status = count($orderDetails) > 0 ? 'member' : 'following';
            return [
                'user' => new UserResource($user),
                'status' => $status,
                'orders' => OrderResource::collection($orderDetails),
            ];
        });

        // Total number of distinct users who follow the vendor or have ordered from the vendor
        $totalCustomer = count($allUserIds);

        return response()->json([
            'total_customer' => $totalCustomer,
            'total_followers' => $totalFollowers,
            // 'active_followers' => $activeFollowers,
            'total_order_users' => count($orderUsers),
            'users' => $userStatus,
        ]);
    }






    public function hasFollowed(Request $request, Vendor $vendor)
    {
        $user = Auth::user();
        $hasFollowed = $user->hasFollowed($vendor);
        return response()->json([
            'has_followed' => $hasFollowed,
        ], 200);
    }

}
