<?php

namespace App\Http\Controllers\Api\Vendor;

use DB;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
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

    public function getVendorFollowers($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);

        // Get all followers
        $followers = $vendor->followers()->get();

        // Total number of followers
        $totalFollowers = $followers->count();

        // Number of followers active in the last 7 days
        $activeFollowers = $vendor->followers()
            ->where('last_active_at', '>=', Carbon::now()->subDays(7))
            ->count();

            $totalOrderUsers = DB::table('orders')
                    ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                    ->where('order_product.vendor_id', $vendorId)
                    ->distinct('orders.user_id')
                    ->count('orders.user_id');
        // test it on production level

        return response()->json([
            'total_followers' => $totalFollowers,
            'active_followers' => $activeFollowers,
            'total_order_users' => $totalOrderUsers,
            'followers' => UserResource::collection($followers),
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
