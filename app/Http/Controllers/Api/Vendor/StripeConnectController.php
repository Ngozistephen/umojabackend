<?php

namespace App\Http\Controllers\Api\Vendor;

use Exception;
use Stripe\Stripe;
use Stripe\Account;
use App\Models\User;
use Stripe\LoginLink;
use App\Models\Vendor;
use Stripe\AccountLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StripeStateToken;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StripeConnectController extends Controller
{  
//    this worked but no return response
    // public function onboard(Request $request, $userId)
    // {
    //     $user = User::findOrFail($userId);
    //     $vendor = $user->vendor ;

    //     if (!$vendor) {
    //         return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
    //     }

    //     if (!$vendor->completed_stripe_onboarding) {
    //         $token = Str::random();

    //         StripeStateToken::create([
    //             'vendor_id' => $vendor->id,
    //             'token' => $token,
    //         ]);
    //     }

    //     if (!$vendor->stripe_account_id) {
    //         Stripe::setApiKey(config('services.stripe.secret_key'));
           

    //         $account = Account::create([
    //             'type' => 'standard',
    //             'country' => config('countries.'.$vendor->country_name),
    //             'email' => $vendor->user->email,
    //             'business_type' => 'individual',
    //             // 'business_type' => $vendor->business_type->name,
    //             'individual' => [
    //                 'first_name' => $vendor->user->first_name,
    //                 'last_name' => $vendor->user->last_name,
    //                 'email' => $vendor->user->email,
    //                 'phone' => $vendor->business_phone_number,
    //                 'business_name' => $vendor->business_name,
    //                 'icon' => $vendor->business_image,
    //                 'brand_color' => $vendor->cover_image,
    //                 'address' => [
    //                     'line1' => $vendor->address,
    //                     'city' => $vendor->city,
    //                     'state' => $vendor->state,
    //                     'postal_code' => $vendor->postal_code,
    //                     'country' => config('countries.'.$vendor->country_name)
    //                 ],
    //             ],
    //             // 'tos_acceptance' => [
    //             //     'date' => time(),
    //             //     'ip' => $request->ip(),
    //             // ],
    //         ]);

    //         $vendor->stripe_account_id = $account->id;
    //         $vendor->save();

    //         $accountLink = AccountLink::create([
    //             'account' => $vendor->stripe_account_id,
    //             'refresh_url' => url('/api/vendor/stripe/refresh_account_link'),
    //            'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage?token=' . $token,
    //             // 'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage',

    //             'type' => 'account_onboarding',
    //         ]);

    //         return response()->json(['url' => $accountLink->url]);
    //     }

    //     // // Generate a login link for the vendor's Stripe account
    //     // Stripe::setApiKey(config('services.stripe.secret'));
    //     // $loginLink = LoginLink::create([
    //     //     'account' => $vendor->stripe_account_id,
    //     // ]);

    //     // return redirect($loginLink->url);
    // }


    public function onboard(Request $request, $userId)
    {
        // Find the user and their associated vendor
        $user = User::findOrFail($userId);
        $vendor = $user->vendor;

        if (!$vendor) {
            Log::error('No associated vendor found for user ID: ' . $userId);
            return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
        }

        if (!$vendor->completed_stripe_onboarding) {
            Log::info('Vendor has not completed Stripe onboarding. Generating token...');
            $token = Str::random();

            // Create a Stripe state token
            StripeStateToken::create([
                'vendor_id' => $vendor->id,
                'token' => $token,
            ]);
        }

        // Check if the vendor already has a Stripe account ID
        if (!$vendor->stripe_account_id) {
            try {
                Log::info('Creating Stripe account for vendor ID: ' . $vendor->id);
                Stripe::setApiKey(config('services.stripe.secret_key'));

                // Create a new Stripe account
                $account = Account::create([
                    'type' => 'standard',
                    'country' => config('countries.'.$vendor->country_name),
                    'email' => $vendor->user->email,
                    'business_type' => 'individual',
                    'individual' => [
                        'first_name' => $vendor->user->first_name,
                        'last_name' => $vendor->user->last_name,
                        'email' => $vendor->user->email,
                        'phone' => $vendor->business_phone_number,
                        'address' => [
                            'line1' => $vendor->address,
                            'city' => $vendor->city,
                            'state' => $vendor->state,
                            'postal_code' => $vendor->postal_code,
                            'country' => config('countries.'.$vendor->country_name),
                        ],
                    ],
                ]);

                // Save the Stripe account ID to the vendor
                $vendor->stripe_account_id = $account->id;
                $vendor->save();

                Log::info('Stripe account created successfully for vendor ID: ' . $vendor->id);

                // Create an account link for onboarding
                $accountLink = AccountLink::create([
                    'account' => $vendor->stripe_account_id,
                    'refresh_url' => url('/api/vendor/stripe/refresh_account_link'),
                    'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage?token=' . $token,
                    'type' => 'account_onboarding',
                ]);

                Log::info('Account link created successfully for vendor ID: ' . $vendor->id);
                return response()->json(['url' => $accountLink->url]);

            } catch (\Exception $e) {
                Log::error('Stripe Account creation failed: ' . $e->getMessage());
                return response()->json(['message' => 'Failed to create Stripe account.'], 500);
            }
        } else {
            Log::info('Vendor already has a Stripe account ID: ' . $vendor->stripe_account_id);

            // Create an account link for existing Stripe account
            try {
                $accountLink = AccountLink::create([
                    'account' => $vendor->stripe_account_id,
                    'refresh_url' => url('/api/vendor/stripe/refresh_account_link'),
                    'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage',
                    'type' => 'account_onboarding',
                ]);

                Log::info('Account link created successfully for existing Stripe account of vendor ID: ' . $vendor->id);
                return response()->json(['url' => $accountLink->url]);

            } catch (\Exception $e) {
                Log::error('Failed to create account link for existing Stripe account: ' . $e->getMessage());
                return response()->json(['message' => 'Failed to create account link for existing Stripe account.'], 500);
            }
        }
    }


    // public function onboard(Request $request, $userId)
    // {
    //     $user = User::findOrFail($userId);
    //     $vendor = $user->vendor;
    
    //     if (!$vendor) {
    //         Log::error('No associated vendor found for user ID: ' . $userId);
    //         return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
    //     }
    
    //     if (!$vendor->completed_stripe_onboarding) {
    //         Log::info('Vendor has not completed Stripe onboarding. Generating token...');
    //         $token = Str::random();
    
    //         StripeStateToken::create([
    //             'vendor_id' => $vendor->id,
    //             'token' => $token,
    //         ]);
    
    //         if (!$vendor->stripe_account_id) {
    //             try {
    //                 Log::info('Creating Stripe account for vendor ID: ' . $vendor->id);
    //                 Stripe::setApiKey(config('services.stripe.secret_key'));
    
    //                 $account = Account::create([
    //                     'type' => 'standard',
    //                     'country' => config('countries.'.$vendor->country_name),
    //                     'email' => $vendor->user->email,
    //                     'business_type' => 'individual',
    //                     'individual' => [
    //                         'first_name' => $vendor->user->first_name,
    //                         'last_name' => $vendor->user->last_name,
    //                         'email' => $vendor->user->email,
    //                         'phone' => $vendor->business_phone_number,
    //                         'address' => [
    //                             'line1' => $vendor->address,
    //                             'city' => $vendor->city,
    //                             'state' => $vendor->state,
    //                             'postal_code' => $vendor->postal_code,
    //                             'country' => config('countries.'.$vendor->country_name),
    //                         ],
    //                     ],
    //                 ]);
    
    //                 $vendor->stripe_account_id = $account->id;
    //                 $vendor->save();
    
    //                 $accountLink = AccountLink::create([
    //                     'account' => $vendor->stripe_account_id,
    //                     'refresh_url' => url('/api/vendor/stripe/refresh_account_link'),
    //                     'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage?token=' . $token,
    //                     'type' => 'account_onboarding',
    //                 ]);
    
    //                 return response()->json(['url' => $accountLink->url]);
    
    //             } catch (Exception $e) {
    //                 Log::error('Stripe Account creation failed: ' . $e->getMessage());
    //                 return response()->json(['message' => 'Failed to create Stripe account.'], 500);
    //             }
    //         }
    
    //         Log::info('Vendor already has a Stripe account ID: ' . $vendor->stripe_account_id);
    //         return response()->json(['message' => 'Stripe account already exists.'], 200);
    //     }
    
    //     Log::info('Stripe onboarding already completed for vendor ID: ' . $vendor->id);
    //     return response()->json(['message' => 'Stripe onboarding already completed.'], 200);
    // }
    

    

    public function saveStripeAccount(Request $request,$token)
    {
        $stripeToken = StripeStateToken::where('token', $token)->first();

        if (is_null($stripeToken)) {
            return response()->json(['message' => 'Token not found'], 404);
        }

        $vendor = Vendor::find($stripeToken->vendor_id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $vendor->update([
            'completed_stripe_onboarding' => true,
        ]);

        return response()->json([
            'message' => 'Stripe account onboarding completed',
            'vendor_id' => $vendor->id,
            'redirect_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage?id=' . $vendor->id
        ]);

    }


    public function refreshAccountLink(Request $request)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
        }

        if (!$vendor->stripe_account_id) {
            return response()->json(['message' => 'No Stripe account associated with this vendor'], 404);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $accountLink = AccountLink::create([
            'account' => $vendor->stripe_account_id,
            'refresh_url' => url('/api/vendor/stripe/refresh_account_link'),
            'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage?token=' . Str::random(),
            'type' => 'account_onboarding',
        ]);

        return response()->json(['url' => $accountLink->url]);
    }



   

    
    // public function onboard(Request $request, $userId)
    // {
    //     $user = User::findOrFail($userId);
    //     $vendor = $user->vendor;

    //     if (!$vendor) {
    //         return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
    //     }

    //     if (!$vendor->completed_stripe_onboarding) {
    //         $token = Str::random();

    //         // Save the token for later use if needed
    //         StripeStateToken::create([
    //             'vendor_id' => $vendor->id,
    //             'token' => $token,
    //         ]);
    //     }

    //     if (!$vendor->stripe_account_id) {
    //         // Ensure you set the correct Stripe API key
    //         Stripe::setApiKey(config('services.stripe.secret_key'));

    //         // Prepare the account creation parameters
    //         $accountParams = [
    //             'type' => 'standard',
    //             'country' => config('countries.'.$vendor->country_name),
    //             'email' => $vendor->user->email,
    //             'business_type' => 'individual',
    //             'individual' => [
    //                 'first_name' => $vendor->user->first_name,
    //                 'last_name' => $vendor->user->last_name,
    //                 'email' => $vendor->user->email,
    //                 'phone' => $vendor->business_phone_number,
    //                 'address' => [
    //                     'line1' => $vendor->address,
    //                     'city' => $vendor->city,
    //                     'state' => $vendor->state,
    //                     'postal_code' => $vendor->postal_code,
    //                     'country' => config('countries.'.$vendor->country_name),
    //                 ],
    //             ],
    //             // TOS acceptance can only be accepted by the account holder on Stripe's interface
    //             // 'tos_acceptance' => [
    //             //     'date' => time(),
    //             //     'ip' => $request->ip(),
    //             // ],
    //         ];

    //         try {
    //             // Create the Stripe account
    //             $account = Account::create($accountParams);

    //             // Save the Stripe account ID to the vendor record
    //             $vendor->stripe_account_id = $account->id;
    //             $vendor->save();

    //             // Create an account link for the vendor to complete the onboarding
    //             $accountLink = AccountLink::create([
    //                 'account' => $vendor->stripe_account_id,
    //                 'refresh_url' => url('/api/vendor/stripe/refresh_account_link'),
    //                 'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage?token=' . $token,
    //                 'type' => 'account_onboarding',
    //             ]);

    //             return response()->json(['url' => $accountLink->url]);
    //         } catch (Exception $e) {
    //             // Handle any errors that occur during Stripe API calls
    //             return response()->json(['error' => $e->getMessage()], 500);
    //         }
    //     }

    //     // If vendor already has a stripe_account_id, handle accordingly
    //     // For example, generate a login link if needed and redirect
    // }

}
