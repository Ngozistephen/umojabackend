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


    // public function getVendorFollowers($vendorId)
    // {
    //     $vendor = Vendor::findOrFail($vendorId);
    //     $followers = $vendor->followers()->get();

    //     return UserResource::collection($followers);
    // }

    // public function getVendorFollowers($vendorId)
    // {
    //     $vendor = Vendor::findOrFail($vendorId);

    //     // Get all followers
    //     $followers = $vendor->followers()->get();

    //     // Total number of followers
    //     $totalFollowers = $followers->count();

    //     // Number of followers active in the last 7 days
    //     $activeFollowers = $vendor->followers()
    //         ->where('last_active_at', '>=', Carbon::now()->subDays(7))
    //         ->count();

    //         $totalOrderUsers = DB::table('orders')
    //                 ->join('order_product', 'orders.id', '=', 'order_product.order_id')
    //                 ->where('order_product.vendor_id', $vendorId)
    //                 ->distinct('orders.user_id')
    //                 ->count('orders.user_id');

    //     $totalCustomer = $totalFollowers +  $totalOrderUsers;
      

    //     return response()->json([
    //         'total_customer' =>   $totalCustomer,
    //         'total_followers' => $totalFollowers,
    //         'active_followers' => $activeFollowers,
    //         'total_order_users' => $totalOrderUsers,
    //         'followers' => UserResource::collection($followers),
    //     ]);

    // }

  

    // working
    // public function getVendorFollowers($vendorId)
    // {
    //     $vendor = Vendor::findOrFail($vendorId);

    //     // Get all followers
    //     $followers = $vendor->followers()->get();

    //     // Total number of followers
    //     $totalFollowers = $followers->count();

    //     // Number of followers active in the last 7 days
    //     $activeFollowers = $vendor->followers()
    //         ->where('last_active_at', '>=', Carbon::now()->subDays(7))
    //         ->count();

    //     // Get users who have ordered from the vendor
    //     $orderUsers = DB::table('orders')
    //         ->join('order_product', 'orders.id', '=', 'order_product.order_id')
    //         ->where('order_product.vendor_id', $vendorId)
    //         ->distinct('orders.user_id')
    //         ->pluck('orders.user_id')
    //         ->toArray();

    //     // Combine followers and order users
    //     $followerIds = $followers->pluck('id')->toArray();
    //     $allUserIds = array_unique(array_merge($followerIds, $orderUsers));

    //     // Retrieve all user data for the combined user ids
    //     $allUsers = User::whereIn('id', $allUserIds)->get();

    //     // Retrieve order details for users who have ordered from the vendor
    //     $orders = Order::whereIn('id', function ($query) use ($vendorId) {
    //         $query->select('order_id')
    //             ->from('order_product')
    //             ->where('vendor_id', $vendorId);
    //         })->whereIn('user_id', $allUserIds)->get()->groupBy('user_id');

    //     // Map users to their status and include order details if applicable
    //     $userStatus = $allUsers->map(function ($user) use ($followerIds, $orderUsers, $orders) {
    //         $status = in_array($user->id, $followerIds) ? 'following' : 'member';
    //         return [
    //             'user' => new UserResource($user, $orders->get($user->id) ?? []),
    //             'status' => $status,
    //         ];
    //     });

    //     // Total number of distinct users who follow the vendor or have ordered from the vendor
    //     $totalCustomer = count($allUserIds);

    //     return response()->json([
    //         'total_customer' => $totalCustomer,
    //         'total_followers' => $totalFollowers,
    //         'active_followers' => $activeFollowers,
    //         'total_order_users' => count($orderUsers),
    //         'followers' => $userStatus,
    //     ]);
    // }


    public function getVendorFollowers($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);

        // Get all followers
        $followers = $vendor->followers()->get();

        // Total number of followers
        $totalFollowers = $followers->count();

        // // Number of followers active in the last 7 days
        // $activeFollowers = $vendor->followers()
        //     ->where('last_active_at', '>=', Carbon::now()->subDays(7))
        //     ->count();

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


    // not tested yet
    // public function getVendorFollowers($vendorId)
    // {
    //     $vendor = Vendor::findOrFail($vendorId);

    //     // Get all followers
    //     $followers = $vendor->followers()->get();
    //     $followerIds = $followers->pluck('id')->toArray();

    //     // Get users who have ordered from the vendor
    //     $orderUserIds = DB::table('orders')
    //         ->join('order_product', 'orders.id', '=', 'order_product.order_id')
    //         ->where('order_product.vendor_id', $vendorId)
    //         ->distinct()
    //         ->pluck('orders.user_id')
    //         ->toArray();

    //     // Combine followers and order users to get all relevant users
    //     $allUserIds = array_unique(array_merge($followerIds, $orderUserIds));
    //     $allUsers = User::whereIn('id', $allUserIds)->get();

    //     // Retrieve order details for users who have ordered from the vendor
    //     $orders = Order::whereIn('user_id', $allUserIds)
    //         ->whereIn('id', function ($query) use ($vendorId) {
    //             $query->select('order_id')
    //                 ->from('order_product')
    //                 ->where('vendor_id', $vendorId);
    //         })
    //         ->get()
    //         ->groupBy('user_id');

    //     // Map users to their status and include order details if applicable
    //     $userStatus = $allUsers->map(function ($user) use ($orders) {
    //         $orderDetails = $orders->get($user->id) ?? [];
    //         $status = count($orderDetails) > 0 ? 'member' : 'following';
    //         return [
    //             'user' => new UserResource($user),
    //             'status' => $status,
    //             'orders' => OrderResource::collection($orderDetails),
    //         ];
    //     });

    //     // Total number of distinct users who follow the vendor or have ordered from the vendor
    //     $totalCustomer = count($allUserIds);

    //     // Total number of followers
    //     $totalFollowers = count($followerIds);

    //     // Number of followers active in the last 7 days
    //     $activeFollowers = $vendor->followers()
    //         ->where('last_active_at', '>=', Carbon::now()->subDays(7))
    //         ->count();

    //     // Total number of users who have ordered from the vendor
    //     $totalOrderUsers = count($orderUserIds);

    //     return response()->json([
    //         'total_customer' => $totalCustomer,
    //         'total_followers' => $totalFollowers,
    //         'active_followers' => $activeFollowers,
    //         'total_order_users' => $totalOrderUsers,
    //         'followers' => $userStatus,
    //     ]);
    // }



    public function hasFollowed(Request $request, Vendor $vendor)
    {
        $user = Auth::user();
        $hasFollowed = $user->hasFollowed($vendor);
        return response()->json([
            'has_followed' => $hasFollowed,
        ], 200);
    }

}
