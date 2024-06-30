<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordResetToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordVendorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required','confirmed', Password::defaults()],
        ]);
    
        // $passwordResetToken = PasswordResetToken::where('email', $request->email)
        //     ->where('token', $request->token)
        //     ->first();
        $passwordResetToken = PasswordResetToken::where('email', strtolower($request->email))
            ->where('token', $request->token)
            ->first();
    
        if (!$passwordResetToken || $this->tokenExpired($passwordResetToken)) {
            return response()->json(['error' => 'Invalid or expired reset token.'], 400);
        }
    
       
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
    
      
        $passwordResetToken->delete();
    
        return response()->json(['message' => 'Password has been reset successfully. You can now use your new password to login.'], 201);
    
    }


    // protected function tokenExpired($passwordResetToken)
    // {
    //     $expirationTime = config('auth.passwords.vendors.expire');
    //     $createdAt = $passwordResetToken->created_at;

    //     return now()->diffInMinutes($createdAt) > $expirationTime;
    // }

    protected function tokenExpired($passwordResetToken)
    {
        $expirationTime = config('auth.passwords.vendors.expire');
        $createdAt = $passwordResetToken->created_at;
        $diffInMinutes = now()->diffInMinutes($createdAt);

        Log::info('Token created at: ' . $createdAt);
        Log::info('Current time: ' . now());
        Log::info('Difference in minutes: ' . $diffInMinutes);
        Log::info('Expiration time: ' . $expirationTime);

        return $diffInMinutes > $expirationTime;
    }
}
