<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\ForgetPasswordMail;
use App\Models\PasswordResetToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ForgetVendorPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $email = $request->email;
        $vendor = User::where('email', $email)->first();

        if (!$vendor) {
            return response([
                'message' => 'Email is Invalid'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = Str::random(60); 

        $passwordResetToken = PasswordResetToken::where('email', $email)->first();

        if ($passwordResetToken) {
            $passwordResetToken->update([
                'token' => $token,
                'created_at' => now(),
            ]);
        } else {
            
            PasswordResetToken::create([
                'email' => $email,
                'token' => $token,
            ]);
        }

        // if it is code is want i use rand(10,100000)
        // $resetLink = route('auth.reset_vendor_password', ['token' => $token]);
        $resetLink = config('app.frontend_url') . 'vendor/newpass/' . $token;

        Mail::to($email)->send(new ForgetPasswordMail($resetLink));

        return response([
            'message' => 'Password reset instructions sent to your email.',
            'reset_link' => $resetLink,
            'expires_in' => '60 minutes',
            'instructions' => 'Follow the link to reset your password. If you didn\'t request this, ignore the email.'
        ], Response::HTTP_OK);
    }
}
