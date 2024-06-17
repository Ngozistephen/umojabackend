<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeConnectController extends Controller
{
    


    public function onboard(Request $request)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor->stripe_account_id) {
            Stripe::setApiKey(config('services.stripe.secret'));

            $account = Account::create([
                'type' => 'standard',
                'country' => $vendor->country_name, // Change this to the vendor's country
                'email' => $vendor->user->email,
                'business_type' => 'individual', // Change based on your requirements
                'individual' => [
                    'first_name' => $vendor->user->first_name,
                    'last_name' => $vendor->user->last_name,
                    'email' => $vendor->user->email,
                    'phone' => $vendor->phone,
                    'address' => [
                        'line1' => $vendor->address_line1,
                        'city' => $vendor->city,
                        'state' => $vendor->state,
                        'postal_code' => $vendor->postal_code,
                        'country' => 'US', // Change to the vendor's country
                    ],
                ],
                'tos_acceptance' => [
                    'date' => time(),
                    'ip' => request()->ip(),
                ],
            ]);

            $vendor->stripe_account_id = $account->id;
            $vendor->save();
        }

        $accountLink = AccountLink::create([
            'account' => $vendor->stripe_account_id,
            'refresh_url' => url('/vendor/onboard'),
            'return_url' => url('/dashboard'),
            'type' => 'account_onboarding',
        ]);

        return response()->json(['url' => $accountLink->url]);
    }
    // public function createStripeAccount(Request $request)
    // {

    //     $vendor = Vendor::find($request->user()->vendor->id);
    //     $stripeRedirectUrl = route('vendor.callback');
    //         Stripe::setApiKey(env('STRIPE_SECRET'));




    // }
}
