<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscoveryPageController extends Controller
{
    
    public function popProducts(Request $request)
    {
        $perPage = $request->query('per_page', 15);

       
        $popularProducts = Product::select('products.*')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->groupBy('products.id')
            ->orderByRaw('COUNT(order_product.product_id) DESC')
            ->paginate($perPage);

        return ProductResource::collection($popularProducts);
    }
}
