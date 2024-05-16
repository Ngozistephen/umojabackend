<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;

class SocialRegisterController extends Controller
{
    public function redirect(string $provider)
    // for front end 
    {
        $this->validateProvider($provider);

       $url =  Socialite::driver($provider)->stateless()->redirect()->redirectUrl();
        // $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

       return response()->json([
        "message" => "Successfully generated $provider redirect URL.",
        "url" => $url,
       ]);    
      
   

    }
    // public function redirect(string $provider)
    // {
    //     $this->validateProvider($provider);

    //     return Socialite::driver($provider)->stateless()->redirect();
   

    // }
    
    // public function callback(Request $request,string $provider)
    // {
    //     $this->validateProvider($provider);
        
    //     $response  = Socialite::driver($provider)->stateless()->user();
       
    //     $user = User::firstWhere(['email' => $response->getEmail()]);

    //     if ($user) {
    //         $user->update([$provider . '_id' => $response->getId()]);
    //     } else {
               
    //         $fullName = $response->getName();

    //         $names = explode(' ', $fullName, 2);
    //         $firstName = $names[0];
    //         $lastName = isset($names[1]) ? $names[1] : null;

          
    //         $user = User::create([
    //             $provider . '_id' => $response->getId(),
    //             'first_name'     =>  $firstName, 
    //             'last_name'      => $lastName,  
    //             'email'          => $response->getEmail(),
    //             'oauth_type'   => $provider,
    //             'password'        => Hash::make($response->getId()),
    //             'terms_accepted' => true, 
    //             'role_id'         => Role::ROLE_CUSTOMER,
                
    //         ]);
    //     }


    //     event(new Registered($user));
        
    //     $device = substr($request->userAgent() ?? '', 0, 255);
    
    //     $token = $user->createToken($device)->accessToken;
    
    //     $role = $user->role->name;

    //     $response = [
    //         'access_token' => $token,
    //         'user' => $user->first_name,
    //         'role' => $role,
    //         'Message' => 'registered successfully.'
    //     ];
        
    //     return response()->json($response,  Response::HTTP_CREATED);
    // }

    public function callback(Request $request, string $provider)
    {
        $this->validateProvider($provider);
    
        try {
            $response = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            // Handle social login provider errors gracefully
            return response()->json(['error' => 'Social login failed'], Response::HTTP_UNAUTHORIZED);
        }
    
        // Check for existing user with verified email
        $user = User::where('email', $response->getEmail())
                //    ->whereNotNull('email_verified_at')
                   ->first();
    
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
                'role_id' => Role::ROLE_CUSTOMER,
            ]);
    
          
    
            event(new Registered($user));
        } else {
            // Existing user - update social media ID if necessary
            if (!$user->$provider . '_id') {
                $user->update([$provider . '_id' => $response->getId()]);
            }
        }
    
        // Generate access token and response (avoid sending sensitive info)
        $device = substr($request->userAgent() ?? '', 0, 255);
        $token = $user->createToken($device)->accessToken;
        $role = $user->role->name;
    
        $response = [
            'access_token' => $token,
            // Only send necessary user information, not first name
            'user' => [
                'email' => $user->email, // Consider including only if needed
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
