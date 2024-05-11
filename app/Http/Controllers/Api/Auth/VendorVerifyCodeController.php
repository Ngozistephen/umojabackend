<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class VendorVerifyCodeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'verification_code' => ['required', 'string', 'min:6', 'max:6'],
        ]);
        $email = $request->email;
        $verificationCode = $request->verification_code;
        
       
        $cachedCode = Cache::get('verification_code_' . $email);    

        if ($cachedCode && $cachedCode === $verificationCode) {
            $user = User::where('email', $email)->whereNull('email_verified_at')->first();
            if ($user) {
                $user->update([
                    'email_verified_at' => now(),
                    'is_verified' => true,
                ]);
                
                $userId = $user->id;
                Cache::forget('verification_code_' . $email);
                    return response()->json(['message' => 'Verification successful',  'user_id' => $userId], 200);
                } else {
                   
                    return response()->json(['message' => 'Email is already verified'], 400);
                }
        } else {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

    }
}
