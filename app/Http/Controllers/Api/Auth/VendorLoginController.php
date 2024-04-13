<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class VendorLoginController extends Controller
{

    public function __invoke(Request $request)
    {
        $request->validate([
           
            'email' => ['required', 'email'],
            'password' => ['required', Password::defaults()],
        
        ]);
 
        $vendor = Vendor::where('email', $request->email)->first();


        if ( ! $vendor || ! Hash::check ($request->password, $vendor->password)) {
            
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $device = substr($request->userAgent() ?? '', 0, 255);
        

        return response()->json([
            'user' => $vendor->first_name,
            'role' => Role::find($vendor->role_id)->name,
            'access_token' => $vendor->createToken($device)->accessToken,
            'message' => 'Logged in successfully.'
        ], Response::HTTP_CREATED);
    }
    
}
