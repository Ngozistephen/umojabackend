<?php

namespace App\Http\Controllers\Api\Customer;


use Log;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\DB;

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

    public function checkout(StoreOrderRequest $request)
    {
        $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();
        $subTotal = 0;
        $orderNumber = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $trackingNumber = 'ID' . substr(uniqid(), -8) . 'RS';
        $totalAmount = 0; 

        foreach ($cartItems as $cartProduct) {
            $product = $cartProduct->product; 
            $subTotal += $product->price * $cartProduct->quantity;
            $totalAmount += $product->price * $cartProduct->quantity; 
            $vendorID = $product->vendor_id;

        

            if ($product->unit_per_item < $cartProduct->quantity || $product->variations->pluck('no_available')->sum() < $cartProduct->quantity) {
                return response()->json([
                    'error' => "Product '{$product->name}' not found in stock"
                ], 404);
            }
        }

        try {
            DB::transaction(function () use ($cartItems,$vendorID, $request,$orderNumber,$trackingNumber, $subTotal,$totalAmount ) {

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'vendor_id' => $vendorID,
                    'shipping_address_id' => $request->shipping_address_id,
                    'billing_address_id' => $request->billing_address_id,
                    'shipping_method_id' => $request->shipping_method_id,
                    'order_number' => $orderNumber,
                    'tracking_number' => $trackingNumber,
                    'sub_total' => $subTotal,
                    'delivery_charge' => $request->delivery_charge,
                    'discount_code_id' => $request->discount_code_id,
                    'total_amount' => $totalAmount, 
                ]);
        
                foreach ($cartItems as $cartProduct) {
                    $randomCode = rand(1000000, 9999999);
        
                    $order->products()->attach($cartProduct->product_id, [
                        'qty' => $cartProduct->quantity,
                        'price' => $cartProduct->product->price,
                        'tracking_id' => $randomCode,
                        'vendor_id' => $cartProduct->product->vendor_id,
                    ]);
        
                    $product = Product::find($cartProduct->product_id);
                    $product->decrement('unit_per_item', $cartProduct->quantity);
                }
        
                // Clear the cart items after successful checkout to uncomment later
                // CartItem::where('user_id', auth()->id())->delete();
        
                return response()->json([
                    'success' => 'Order placed successfully',
                ], 200);
            });
        } catch (\Exception $exception) {
            return response()->json(['Error' => 'Error Happened. Try Again or Contact us.' ]);
        }
       

    }

   


    

}
