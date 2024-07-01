<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Log;
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
    
        $passwordResetToken = PasswordResetToken::where('email', $request->email)
            ->where('token', $request->token)
            ->first();
        // $passwordResetToken = PasswordResetToken::where('email', strtolower($request->email))
        //     ->where('token', $request->token)
        //     ->first();
    
        if (!$passwordResetToken || $this->tokenExpired($passwordResetToken)) {
            return response()->json(['error' => 'Invalid or expired reset token.'], 400);
        }
    
       
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
    
      
        $passwordResetToken->delete();
    
        return response()->json(['message' => 'Password has been reset successfully. You can now use your new password to login.'], 201);
    
    }


    protected function tokenExpired($passwordResetToken)
    {
        $expirationTime = config('auth.passwords.vendors.expire');
        $createdAt = $passwordResetToken->created_at;

        return now()->diffInMinutes($createdAt) > $expirationTime;
    }

    // protected function tokenExpired($passwordResetToken)
    // {
    //     $expirationTime = config('auth.passwords.vendors.expire');
    //     $createdAt = $passwordResetToken->created_at;
    //     $diffInMinutes = now()->diffInMinutes($createdAt);

    //     Log::info('Token created at: ' . $createdAt);
    //     Log::info('Current time: ' . now());
    //     Log::info('Difference in minutes: ' . $diffInMinutes);
    //     Log::info('Expiration time: ' . $expirationTime);

    //     return $diffInMinutes > $expirationTime;
    // }



    // public function __invoke(Request $request)
    // {
    //     $request->validate([
    //         'token' => ['required'],
    //         'email' => ['required', 'email'],
    //         'password' => ['required','confirmed', Password::defaults()],
    //     ]);

    //     Log::info('Reset password request received', $request->all());

    //     $passwordResetToken = PasswordResetToken::where('email', $request->email)
    //         ->where('token', $request->token)
    //         ->first();

    //     if (!$passwordResetToken) {
    //         Log::error('Password reset token not found for email: ' . $request->email . ' and token: ' . $request->token);
    //         return response()->json(['error' => 'Invalid or expired reset token.'], 400);
    //     }

    //     if ($this->tokenExpired($passwordResetToken)) {
    //         Log::error('Password reset token expired for email: ' . $request->email);
    //         return response()->json(['error' => 'Invalid or expired reset token.'], 400);
    //     }

    //     Log::info('Resetting password for email: ' . $request->email);

    //     User::where('email', $request->email)
    //         ->update(['password' => Hash::make($request->password)]);

    //     Log::info('Password reset successful for email: ' . $request->email);

    //     $passwordResetToken->delete();

    //     return response()->json(['message' => 'Password has been reset successfully. You can now use your new password to login.'], 201);
    // }

    // protected function tokenExpired($passwordResetToken)
    // {
    //     $expirationTime = config('auth.passwords.vendors.expire');
    //     $createdAt = $passwordResetToken->created_at;
    //     $diffInMinutes = now()->diffInMinutes($createdAt);

    //     Log::info('Token created at: ' . $createdAt);
    //     Log::info('Current time: ' . now());
    //     Log::info('Difference in minutes: ' . $diffInMinutes);
    //     Log::info('Expiration time: ' . $expirationTime);

    //     return $diffInMinutes > $expirationTime;
    // }
}
