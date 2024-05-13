<?php

namespace App\Http\Controllers\Api\Vendor;

use Stripe\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Stripe\Exception\InvalidRequestException;

class SubscriptionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function subscribe(Request $request, string $plan = 'price_1PFHkMP7XylLhhgiIodwtUZX')
    {
        return $request->user()
                ->newSubscription('prod_Q5SfkqMREoWTkc', $plan)
                // ->trialDays(5)
                // ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('vendor.subscription_success'),
                    'cancel_url' => route('vendor.subscription_cancel'),
                ]);
    }

    public function success(Request $request)
    {
        // Get the Stripe payment method ID from the session
        $paymentMethodId = $request->session()->get('success_payment_method_id');

        try {
            
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
          
            $cardDetails = [
                'last4' => $paymentMethod->card->last4,
                'brand' => $paymentMethod->card->brand,
                'exp_month' => $paymentMethod->card->exp_month,
                'exp_year' => $paymentMethod->card->exp_year,
            ];
            
           
            return response()->json($cardDetails);
        } catch (InvalidRequestException $e) {
            // Handle the exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cancel(Request $request)
    {
        
        $subscription = $request->user()->subscription('prod_Q5SfkqMREoWTkc');
        
     
        $subscription->cancel();

      
        return new Response("Your subscription has been canceled successfully.");
    }
}
