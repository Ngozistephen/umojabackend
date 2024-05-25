<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;

class PromoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $vendor = Auth::user()->vendor;
        // Calculate the total count of products with a compare_at_price
        $totalProductsWithCompareAtPrice = Product::where('vendor_id', $vendor->id)
            ->whereNotNull('compare_at_price')
            ->where('compare_at_price', '>', 0)
            ->count();

        // Fetch the paginated products with compare_at_price
        $products = Product::with('variations')
            ->where('vendor_id', $vendor->id)
            ->whereNotNull('compare_at_price')
            ->where('compare_at_price', '>', 0)
            ->latest()
            ->paginate(10);

        // Return the products along with the total count
        return response()->json([
            'total' => $totalProductsWithCompareAtPrice,
            'products' => ProductResource::collection($products),
        ]);
    }
}
