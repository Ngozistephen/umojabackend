<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Role;
use App\Models\User;
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
 
        $user = User::where('email', $request->email)->first();


        if ( ! $user || ! Hash::check ($request->password, $user->password)) {
            
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->role_id !== 1 && $user->role_id !== 2) {
            throw ValidationException::withMessages([
                'email' => ['You do not have permission to access this resource.'],
            ]);
        }

        if (!$user->is_verified) {
            throw ValidationException::withMessages([
                'email' => ['Your account is not yet verified., please click o resend button'],
            ]);
        }
        
        $device = substr($request->userAgent() ?? '', 0, 255);
        

        return response()->json([
            'user' => $user->first_name,
            'role' => Role::find($user->role_id)->name,
            'user_id' => $user->id,
            'access_token' => $user->createToken($device)->accessToken,
            'message' => 'Logged in successfully.'
        ], Response::HTTP_CREATED);
    }
    
}
