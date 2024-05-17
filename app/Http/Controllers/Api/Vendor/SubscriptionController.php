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
    // public function subscribe(Request $request, string $plan = 'price_1PFHkMP7XylLhhgiIodwtUZX')
    // {
    //     $user = $request->user();
    //     $subscription = $user->newSubscription('prod_Q5SfkqMREoWTkc', $plan)
    //             // ->trialDays(5)
    //             // ->allowPromotionCodes()
    //             ->checkout([
    //                 'success_url' => route('vendor.subscription_success'),
    //                 'cancel_url' => route('vendor.subscription_cancel'),
    //             ]);
    //     $vendor = $user->vendor;
       
    //     $vendor->update(['complete_setup' => true]);
    
    //     return $subscription;
    // }

    public function subscribe(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            
            $user->update(['complete_setup' => true]);
            
            // Retrieve the updated user data to include complete_setup in the response
            $user = $user->fresh();
    
            // Include complete_setup in the response
            $responseData = [
                'message' => 'Vendor setup completed successfully',
                'complete_setup' => $user->complete_setup,
            ];
    
            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        
          
        $user = $request->user();

   
        $vendor = $user->vendor;

        $subscription = $user->subscription('prod_Q5SfkqMREoWTkc');
        $subscription->cancel();

   
        $vendor->update(['complete_setup' => false]);

      
        return new Response("Your subscription has been canceled successfully.");
    }
}
