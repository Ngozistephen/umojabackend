<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;

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
            })->with(['products' => function ($query) use ($vendorId) {
                $query->where('order_product.vendor_id', $vendorId);
            }])->latest()->paginate(20);

 
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
