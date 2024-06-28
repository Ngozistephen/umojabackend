<?php

namespace App\Http\Controllers\Api\Vendor;

use Stripe\Account;
use Stripe\AccountLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                'business_type' =>'individual', 
                // 'business_type' => $vendor->business_type->name, 
                'individual' => [
                    'first_name' => $vendor->user->first_name,
                    'last_name' => $vendor->user->last_name,
                    'email' => $vendor->user->email,
                    'phone' => $vendor->user->phone_number,
                    'address' => [
                        'line1' => $vendor->address,
                        'city' => $vendor->city,
                        'state' => $vendor->state,
                        'postal_code' => $vendor->postal_code,
                        'country' => $vendor->country_name, 
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
            'refresh_url' => url('/vendor/connect_account'),
            'return_url' => url('https://umoja-store.netlify.app/vendor/dashboard/Homepage'),
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
