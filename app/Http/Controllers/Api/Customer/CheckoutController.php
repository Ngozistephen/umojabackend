<?php

namespace App\Http\Controllers\Api\Customer;


use Log;
use Stripe\Stripe;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use Stripe\Exception\ApiErrorException;
use App\Http\Requests\StoreOrderRequest;
use App\Notifications\SendOrderNotification;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
   

     public function allOrders(Request $request)
     {
         // Fetch orders associated with the logged-in vendor, ordered by the latest
         $vendorOrders = Auth::user()->vendor->orders()->latest()->paginate(20);
         
         // Format orders using the resource class
         return OrderResource::collection($vendorOrders);
     }
// this is working with stripe 
    public function checkout(StoreOrderRequest $request)
    {
        $products = $request->input('products');
        $subTotal = 0;
        $orderNumber = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $trackingNumber = 'ID' . substr(uniqid(), -8) . 'RS';
        $totalAmount = 0;

        foreach ($products as $product) {
            $subTotal += $product['price'] * $product['quantity'];
            $totalAmount += $product['price'] * $product['quantity'];
            $vendorID = $product['vendor_id'];

            if (isset($product['variations']) && isset($product['variations']['no_available'])) {
                if ($product['unit_per_item'] < $product['quantity'] || $product['variations']['no_available'] < $product['quantity']) {
                    return response()->json([
                        'error' => "Product '{$product['name']}' not found in stock"
                    ], 404);
                }
            } else {
                \Log::warning("No 'no_available' key found in 'variations' for product '{$product['name']}'");
            }
        }

        try {
            $user = Auth::user();
            $user->createOrGetStripeCustomer();
            $paymentMethodId = $request->input('payment_method_id');
            $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);
            $user->updateDefaultPaymentMethod($paymentMethod->payment_method);

            $returnUrl = 'https://umoja-store.netlify.app/order/summary';
            $payment = $user->charge(
                $totalAmount * 100, // Amount in cents
                $paymentMethod->payment_method, // Payment method ID
                [
                    'currency' => 'eur',
                    'metadata' => [
                        'order_number' => $orderNumber,
                    ],
                    'return_url' => $returnUrl,
                ]
            );
    
            $paymentIntent = $payment->asStripePaymentIntent();

            

            \Log::info('paymentIntent: ' . json_encode($paymentIntent));

            DB::transaction(function () use ($products, $vendorID, $request, $orderNumber, $trackingNumber, $subTotal, $totalAmount, $paymentIntent, $user) {
                $order = $user->orders()->create([
                    'vendor_id' => $vendorID,
                    'shipping_address_id' => $request->shipping_address_id,
                    'payment_method_id' => $request->payment_method_id,
                    'shipping_method_id' => $request->shipping_method_id,
                    'order_number' => $orderNumber,
                    'tracking_number' => $trackingNumber,
                    'sub_total' => $subTotal,
                    'delivery_charge' => $request->delivery_charge,
                    'discount_code_id' => $request->discount_code_id,
                    'total_amount' => $totalAmount,
                    'transaction_id' => $paymentIntent->id,
                ]);

                foreach ($products as $product) {
                    $randomCode = rand(1000000, 9999999);
                    $order->products()->attach($product['id'], [
                        'qty' => $product['quantity'],
                        'price' => $product['price'],
                        'tracking_id' => $randomCode,
                        'vendor_id' => $product['vendor_id'],
                    ]);

                    $productModel = Product::find($product['id']);
                    $productModel->decrement('unit_per_item', $product['quantity']);
                }

                $order->update(['paid_at' => now(), 'payment_status' => 'paid']);
            });

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'last_four_digits' => Auth::user()->defaultPaymentMethod()->card->last4,
                'success' => 'Order placed successfully',
            ], 200);
        } catch (ApiErrorException $exception) {
            return response()->json(['error' => 'Error processing payment'], 500);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


   

    // this is working perfect, saving the order to database but no stripe
    // public function checkout(StoreOrderRequest $request)
    // {
    //     $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();
    //     $subTotal = 0;
    //     $orderNumber = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    //     $trackingNumber = 'ID' . substr(uniqid(), -8) . 'RS';
    //     $totalAmount = 0; 

    //     foreach ($cartItems as $cartProduct) {
    //         $product = $cartProduct->product; 
    //         $subTotal += $product->price * $cartProduct->quantity;
    //         $totalAmount += $product->price * $cartProduct->quantity; 
    //         $vendorID = $product->vendor_id;

        

    //         if ($product->unit_per_item < $cartProduct->quantity || $product->variations->pluck('no_available')->sum() < $cartProduct->quantity) {
    //             return response()->json([
    //                 'error' => "Product '{$product->name}' not found in stock"
    //             ], 404);
    //         }
    //     }

    //     try {
    //         DB::transaction(function () use ($cartItems,$vendorID, $request,$orderNumber,$trackingNumber, $subTotal,$totalAmount ) {

    //             $order = Order::create([
    //                 'user_id' => auth()->id(),
    //                 'vendor_id' => $vendorID,
    //                 'shipping_address_id' => $request->shipping_address_id,
    //                 'billing_address_id' => $request->billing_address_id,
    //                 'shipping_method_id' => $request->shipping_method_id,
    //                 'order_number' => $orderNumber,
    //                 'tracking_number' => $trackingNumber,
    //                 'sub_total' => $subTotal,
    //                 'delivery_charge' => $request->delivery_charge,
    //                 'discount_code_id' => $request->discount_code_id,
    //                 'total_amount' => $totalAmount, 
    //             ]);
        
    //             foreach ($cartItems as $cartProduct) {
    //                 $randomCode = rand(1000000, 9999999);
        
    //                 $order->products()->attach($cartProduct->product_id, [
    //                     'qty' => $cartProduct->quantity,
    //                     'price' => $cartProduct->product->price,
    //                     'tracking_id' => $randomCode,
    //                     'vendor_id' => $cartProduct->product->vendor_id,
    //                 ]);
        
    //                 $product = Product::find($cartProduct->product_id);
    //                 $product->decrement('unit_per_item', $cartProduct->quantity);
    //             }
        
    //             // Clear the cart items after successful checkout to uncomment later
    //             // CartItem::where('user_id', auth()->id())->delete();
        
    //         });
    //             return response()->json([
    //                 'success' => 'Order placed successfully',
    //             ], 200);
    //             // return OrderResource::collection(Order::all()); for all
    //     } catch (\Exception $exception) {
    //         return response()->json(['Error' => 'Error Happened. Try Again or Contact us.' ]);
    //     }
       

    // }



   


    

}
