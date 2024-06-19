<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

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


    public function getTopSellingProducts(Request $request)
    {
        $perPage = $request->query('per_page', 15);

       
        $topSellingProducts = Product::select('products.*')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->groupBy('products.id')
            ->orderByRaw('COUNT(order_product.product_id) DESC')
            ->paginate($perPage);

       
        return ProductResource::collection($topSellingProducts);
    }
}
