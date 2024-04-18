<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class VendorPasswordSetupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $token)
    {
        // Find the user by the token
        $user = User::where('password_setup_token', $token)->first();

        if (! $user) {
            return response()->json(['error' => 'Invalid token'], 404);
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Update vendor's password
        $user->password = Hash::make($request->password);
        $user->password_setup_token = null; 
        $user->save();

      
        return response()->json(['message' => 'Password set successfully']);
    }
}
