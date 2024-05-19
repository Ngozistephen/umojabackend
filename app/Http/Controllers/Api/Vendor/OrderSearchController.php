<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderSearchResource;

class OrderSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $vendorId = Auth::user()->vendor->id;

        $ordersQuery = Order::whereHas('products', function ($query) use ($vendorId) {
                $query->where('order_product.vendor_id', $vendorId);
            })
            ->with(['products' => function ($query) use ($vendorId) {
                $query->where('order_product.vendor_id', $vendorId);
            }])
            ->when($request->search_global, function ($query) use ($request) {
                $searchTerm = '%' . $request->search_global . '%';$
                $query->where(function ($q) use ($request,$searchTerm) { 
                    $q->where('id', $request->search_global)
                        ->orWhere('order_number', 'like', $searchTerm)
                        ->orWhereHas('shippingAddress', function ($q) use ($searchTerm) {
                            $q->where('shipping_full_name', 'like', $searchTerm);
                        });
                });
            })
            ->latest()
            ->paginate(20); // Adjust the pagination limit as needed

        return OrderSearchResource::collection($ordersQuery);
    }
}
