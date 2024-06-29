<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $vendorId = Auth::user()->vendor->id;
    
        $orders = Order::whereHas('products', function ($query) use ($vendorId) {
                $query->where('order_product.vendor_id', $vendorId);
            })
            ->with(['products' => function ($query) use ($vendorId) {
                $query->where('order_product.vendor_id', $vendorId);
            }])
            ->when($request->has('unfulfilled') && $request->unfulfilled === 'true', function ($query) {
                $query->where('fulfillment_status', 'unfulfilled');
            })
            ->when($request->has('unpaid') && $request->unpaid === 'true', function ($query) {
                $query->where('payment_status', 'pending');
            })
            ->when($request->has('open') && $request->open === 'true', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->when($request->has('closed') && $request->closed === 'true', function ($query) {
                $query->where('payment_status', 'paid')->where('fulfillment_status', 'fulfilled');
            })
            ->latest()
            ->paginate(20);
    
        return OrderResource::collection($orders);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $vendorId = Auth::user()->vendor->id;
        if ($order->products()->where('order_product.vendor_id', $vendorId)->exists()) {
            $order->update(['read' => true]);   
            return new OrderResource($order);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
