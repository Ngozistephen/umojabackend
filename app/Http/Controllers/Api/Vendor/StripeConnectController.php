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
use Stripe\Exception\InvalidRequestException;

class StripeConnectController extends Controller
{  
//    this worked but no return response
    public function onboard(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $vendor = $user->vendor ;

        if (!$vendor) {
            return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
        }

        if (!$vendor->completed_stripe_onboarding) {
            $token = Str::random();

            StripeStateToken::create([
                'vendor_id' => $vendor->id,
                'token' => $token,
            ]);
        }

        if (!$vendor->stripe_account_id) {
            Stripe::setApiKey(config('services.stripe.secret_key'));
           

            $account = Account::create([
                'type' => 'standard',
                'country' => config('countries.'.$vendor->country_name),
                'email' => $vendor->user->email,
                'business_type' => 'individual',
                // 'business_type' => $vendor->business_type->name,
                'individual' => [
                    'first_name' => $vendor->user->first_name,
                    'last_name' => $vendor->user->last_name,
                    'email' => $vendor->user->email,
                    'phone' => $vendor->business_phone_number,
                    'business_name' => $vendor->business_name,
                    'icon' => $vendor->business_image,
                    'brand_color' => $vendor->cover_image,
                    'address' => [
                        'line1' => $vendor->address,
                        'city' => $vendor->city,
                        'state' => $vendor->state,
                        'postal_code' => $vendor->postal_code,
                        'country' => config('countries.'.$vendor->country_name)
                    ],
                ],
                // 'tos_acceptance' => [
                //     'date' => time(),
                //     'ip' => $request->ip(),
                // ],
            ]);

            $vendor->stripe_account_id = $account->id;
            $vendor->save();

            $accountLink = AccountLink::create([
                'account' => $vendor->stripe_account_id,
                'refresh_url' => url('/api/vendor/stripe/refresh_account_link'),
               'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage?token=' . $token,
                // 'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage',

                'type' => 'account_onboarding',
            ]);

            return response()->json(['url' => $accountLink->url]);
        }

        // // Generate a login link for the vendor's Stripe account
        // Stripe::setApiKey(config('services.stripe.secret'));
        // $loginLink = LoginLink::create([
        //     'account' => $vendor->stripe_account_id,
        // ]);

        // return redirect($loginLink->url);
    }
    

    

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
    
        Stripe::setApiKey(config('services.stripe.secret_key'));
    
        try {
            // Log the data being sent to Stripe
            Log::info('Creating Stripe Account Link', [
                'vendor_id' => $vendor->id,
                'stripe_account_id' => $vendor->stripe_account_id,
            ]);
    
            $accountLink = AccountLink::create([
                'account' => $vendor->stripe_account_id,
                'refresh_url' => url('/api/vendor/stripe/refresh_account_link'),
                'return_url' => config('app.frontend_url') . '/vendor/dashboard/Homepage?token=' . Str::random(),
                'type' => 'account_onboarding',
            ]);
    
            // Log the created account link
            Log::info('Stripe Account Link Created', [
                'url' => $accountLink->url,
            ]);
    
            return response()->json(['url' => $accountLink->url]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Log the error message for debugging
            Log::error('Stripe Account Link Creation Error', [
                'error' => $e->getMessage(),
            ]);
    
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }






}
