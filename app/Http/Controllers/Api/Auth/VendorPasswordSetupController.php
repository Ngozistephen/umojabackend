<?php

namespace App\Http\Controllers\Api\Auth;

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
        // Find the vendor by the token
        $vendor = Vendor::where('password_setup_token', $token)->first();

        if (!$vendor) {
            return response()->json(['error' => 'Invalid token'], 404);
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Update vendor's password
        $vendor->password = Hash::make($request->password);
        $vendor->password_setup_token = null; 
        $vendor->save();

      
        return response()->json(['message' => 'Password set successfully']);
    }
}
