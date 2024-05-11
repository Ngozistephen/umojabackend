<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\ResendVerificationCodeMail;

class VerificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
        ]);
    
        $email = $request->email;
    
       
        $verificationCode = mt_rand(100000, 999999);
     
        $cacheKey = 'verification_code_' . $email;
        Cache::put($cacheKey, $verificationCode, now()->addMinutes(30));

        $user = User::where('email', $email)->first();

        Mail::to($email)->send(new ResendVerificationCodeMail($user, $verificationCode));
    
        return response()->json(['message' => 'Verification code resent successfully'], 200);
    }
}
