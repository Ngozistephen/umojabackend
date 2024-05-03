<?php

namespace App\Http\Controllers\Api\Customer;

use DB;
use Log;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    // public function __invoke(StoreOrderRequest $request)
    // {
        
    //     // Start a database transaction
    //     \DB::beginTransaction();

    //     try {
    //         // Calculate the total order amount
    //         $subTotal = 0;
    //         foreach ($request->items as $item) {
    //             $product = Product::findOrFail($item['product_id']);
    //             $subTotal += $product->price * $item['quantity'];
    //         }

    //         // Create a new order
    //         $order = Order::create([
    //             'user_id' => $request->user()->id,
    //             'shipping_address_id' => $request->shipping_address_id,
    //             'billing_address_id' => $request->billing_address_id,
    //             'shipping_method_id' => $request->shipping_method_id,
    //             'sub_total' => $request->sub_total,
    //             'delivery_charge' => $request->delivery_charge,
    //             'total_amount' => $subTotal,
    //         ]);

    //         // Add order items
    //         $totalAmount = 0;
    //         foreach ($request->items as $item) {
    //             $product = Product::findOrFail($item['product_id']);
    //             OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'user_id' => $request->user()->id,
    //                 'product_id' => $item['product_id'],
    //                 'vendor_id' => $product->user?->vendor->id, 
    //                 'price' => $product->price,
    //                 'quantity' => $item['quantity'],
    //             ]);
    //             $totalAmount += $product->price * $item['quantity'];
    //         }

    //         // Update order total amount and quantity
    //         $order->update([
    //             'sub_total' => $totalAmount,
    //             'total_amount' => $totalAmount + $request->delivery_charge,
    //             'quantity' => count($request->items),
    //         ]);

    //         // Commit the transaction
    //         \DB::commit();

    //         // Return the order details
    //         return response()->json(['order' => $order], 201);
    //     } catch (\Exception $e) {
    //         // Rollback the transaction if an error occurs
    //         \DB::rollback();
    //         return response()->json(['message' => 'Failed to checkout'], 500);
    //     }
    // }

    // for chatGBt
    // public function checkout(StoreOrderRequest $request)
    // {
    //     \DB::beginTransaction();

    //     try {
    //         $subTotal = 0;
    //         foreach ($request->items as $item) {
    //             $product = Product::findOrFail($item['product_id']);
    //             $subTotal += $product->price * $item['quantity'];
    //         }

    //         \Log::info('item....: ' .$item);
    //         $orderNumber = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

    //         \Log::info('item....: ' . $orderNumber);
    //         $trackingNumber = 'ID' . substr(uniqid(), -8) . 'RS';

    //         \Log::info('item....: ' .  $trackingNumber);
    //         $order = Order::create([
    //             'user_id' => $request->user()->id,
    //             'shipping_address_id' => $request->shipping_address_id,
    //             'billing_address_id' => $request->billing_address_id,
    //             'shipping_method_id' => $request->shipping_method_id,
    //             'order_number' => $orderNumber,
    //             'tracking_number' => $trackingNumber,
    //             'sub_total' => $subTotal,
    //             'delivery_charge' => $request->delivery_charge,
    //             'discount_code_id' => $request->discount_code_id,
    //             'total_amount' => $subTotal + $request->delivery_charge,
    //         ]);

    //         \Log::info('order....: ' .   $order);
    //         $cartItems = CartItem::where('user_id', $request->user()->id)->get();

    //         // Convert cart items to order item format
    //         $orderItems = $cartItems->map(function ($cartItem) {
    //             $product = Product::findOrFail($cartItem->product_id);
    //             return [
    //                 'user_id' => $cartItem->user_id,
    //                 'product_id' => $cartItem->product_id,
    //                 'quantity' => $cartItem->quantity,
    //                 'price' => $product->price,
    //                 'vendor_id' => $product->user->vendor->id,
                 
    //             ];
    //         });
    //         \Log::info('orderitems....: ' .   $orderItems);
    //         $order->orderItems()->createMany($orderItems);

    //         // $cartItems->each->delete();
       

    //         DB::commit();

    //         \Log::info('orderfinial....: ' .    $order);
    //         return response()->json(['order' => $order], 201);
    //     } catch (\Exception $e) {
    //         \DB::rollback();
    //         return response()->json(['message' => 'Failed to checkout'], 500);
    //     }
    // }

    public function checkout(StoreOrderRequest $request)
    {
        \DB::beginTransaction();

        try {
            $subTotal = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subTotal += $product->price * $item['quantity'];
            }

            \Log::info('item....: ' .$item);
            $orderNumber = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

            \Log::info('item....: ' . $orderNumber);
            $trackingNumber = 'ID' . substr(uniqid(), -8) . 'RS';

            \Log::info('item....: ' .  $trackingNumber);
            $order = Order::create([
                'user_id' => $request->user()->id,
                'shipping_address_id' => $request->shipping_address_id,
                'billing_address_id' => $request->billing_address_id,
                'shipping_method_id' => $request->shipping_method_id,
                'order_number' => $orderNumber,
                'tracking_number' => $trackingNumber,
                'sub_total' => $subTotal,
                'delivery_charge' => $request->delivery_charge,
                'discount_code_id' => $request->discount_code_id,
                'total_amount' => $subTotal + $request->delivery_charge,
            ]);

            \Log::info('order....: ' .   $order);
            $cartItems = CartItem::where('user_id', $request->user()->id)->get();

            // Convert cart items to order item format
            $orderItems = $cartItems->map(function ($cartItem) {
                $product = Product::findOrFail($cartItem->product_id);
                return [
                    'user_id' => $cartItem->user_id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                    'vendor_id' => $product->user->vendor->id,
                 
                ];
            });
            \Log::info('orderitems....: ' .   $orderItems);
            $order->orderItems()->createMany($orderItems);

            // $cartItems->each->delete();
       

            DB::commit();

            \Log::info('orderfinial....: ' .    $order);
            return response()->json(['order' => $order], 201);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => 'Failed to checkout'], 500);
        }
    }


    

}
