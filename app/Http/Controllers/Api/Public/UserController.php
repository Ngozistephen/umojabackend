<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Query the database to retrieve user details
        // $user = User::with('vendor')->findOrFail($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
       
        $user->load(['vendor.shippingZones', 'vendor.policy']);

        // Transform user data using UserResource
        return new UserResource($user);

       
    }

    public function update (User $user, UpdateUserRequest $request)
    {
        // Update user data
        $user->update($request->validated());
        $user->update([
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);
        $vendor = $user->vendor ?? null;
        if ( $vendor) {
            $vendor->update([
                'phone_number' => $request->phone_number,
                'country_name' => $request->country_name,
                'company' => $request->company,
                'address' => $request->address,
                'rep_country' => $request->rep_country,
                'state' => $request->state,
                'city' => $request->city,
                'business_bio' => $request->business_bio,
                'twitter_handle' => $request->twitter_handle,
                'facebook_handle' => $request->facebook_handle,
                'instagram_handle' => $request->instagram_handle,
                'youtube_handle' => $request->youtube_handle,
                'building_name' => $request->building_name,
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'name_on_account' => $request->name_on_account,
                'sort_code' => $request->sort_code,
                'swift_code' => $request->swift_code,
                'iban' => $request->iban,
            ]);
        }
       
        return response()->json(['message' => 'User updated successfully', 'user' => new UserResource($user)], 200);
    }
    // public function update (User $user, UpdateUserRequest $request)
    // {
    //     // Update user data
    //     $user->update($request->validated());

    //     if ($user->vendor) {
    //         $user->vendor->update([
    //             'phone_number' => $request->input('phone_number'),
    //             'country_name' => $request->input('country_name'),
    //             'company' => $request->input('company'),
    //             'address' => $request->input('address'),
    //             'rep_country' => $request->input('rep_country'),
    //             'state' => $request->input('state'),
    //             'city' => $request->input('city'),
    //             'business_bio' => $request->input('business_bio'),
    //             'twitter_handle' => $request->input('twitter_handle'),
    //             'facebook_handle' => $request->input('facebook_handle'),
    //             'instagram_handle' => $request->input('instagram_handle'),
    //             'youtube_handle' => $request->input('youtube_handle'),
    //             'building_name' => $request->input('building_name'),
    //             'bank_name' => $request->input('bank_name'),
    //             'bank_account_number' => $request->input('bank_account_number'),
    //             'name_on_account' => $request->input('name_on_account'),
    //             'sort_code' => $request->input('sort_code'),
    //             'swift_code' => $request->input('swift_code'),
    //             'iban' => $request->input('iban'),
    //         ]);
    //     }
       
    //     return response()->json(['message' => 'User updated successfully', 'user' => new UserResource($user)], 200);
    // }
    

    public function destroy(User $user)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if (Auth::id() !== $user->id) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
