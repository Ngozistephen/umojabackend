<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Mail\VendorSetupAccountMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;

class SocialVendorController extends Controller
{
    public function vendor_redirect(string $provider)
    // for front end 
    {
        $this->validateProvider($provider);

    //    $url =  Socialite::driver($provider)->stateless()->redirect()->redirectUrl();
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

       return response()->json([
        "message" => "Successfully generated $provider redirect URL.",
        "url" => $url,
       ]);    
      
   

    }



    public function vendor_callback(Request $request, string $provider)
    {
        $this->validateProvider($provider);
    
        try {
            $response = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            // Handle social login provider errors gracefully
            return response()->json(['error' => 'Social login failed'], Response::HTTP_UNAUTHORIZED);
        }
    
        // Check for existing user with verified email
        $user = User::where('email', $response->getEmail())->first();
    
        if (!$user) {
            // New user - create user record
            $fullName = $response->getName();
            $names = explode(' ', $fullName, 2);
            $firstName = $names[0];
            $lastName = isset($names[1]) ? $names[1] : null;
    
            $user = User::create([
                $provider . '_id' => $response->getId(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $response->getEmail(),
                'oauth_type' => $provider,
                'password' => Hash::make($response->getId()),
                'terms_accepted' => true,
                'role_id' => Role::ROLE_VENDOR,
                'is_verified' => true,
            ]);
    
          
            // $verificationCode = mt_rand(100000, 999999); 
  
            // $cacheKey = 'verification_code_' . $request->email;
            // Cache::put($cacheKey, $verificationCode, now()->addMinutes(30));
    
            // Mail::to($user->email)->send(new VendorSetupAccountMail($user, $verificationCode));
            $response = [
                'user_email' => $user->email, 
                'message' => 'Vendor account created successfully. Please Setup your profile',
            ];
            return response()->json($response, Response::HTTP_CREATED);
    
           
        } else {
            
            if (!$user->{$provider . '_id'}) {
                $user->update([$provider . '_id' => $response->getId()]);
            }
        }
    

        $device = substr($request->userAgent() ?? '', 0, 255);
        $token = $user->createToken($device)->accessToken;
        $role = $user->role->name;
    
        $response = [
            'access_token' => $token,
           
            'user' => [
                'email' => $user->email, 
                'name' => $user->first_name,
                'role' => $role,
            ],
            'message' => 'Login successful.',
        ];
    
        return response()->json($response, Response::HTTP_CREATED);
    }


    protected function validateProvider(string $provider): array
    {
        return $this->getValidationFactory()->make(
            ['provider' => $provider],
            ['provider' => 'in:google,apple,facebook']
        )->validate();
    }



}
