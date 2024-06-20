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
        

       
        $popularProducts = Product::select('products.*')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->groupBy('products.id')
            ->orderByRaw('COUNT(order_product.product_id) DESC')
            ->paginate(10);

        return ProductResource::collection($popularProducts);
    }


    public function getTopSellingProducts(Request $request)
    {
        

       
        $topSellingProducts = Product::select('products.*')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->groupBy('products.id')
            ->orderByRaw('COUNT(order_product.product_id) DESC')
            ->paginate(10);

       
        return ProductResource::collection($topSellingProducts);
    }


    public function productsOnPromo(Request $request)
    {
        
        $products = Product::with('vendor', 'variations')
            ->whereNotNull('compare_at_price')
            ->where('compare_at_price', '>', 0)
            ->latest()
            ->paginate(10);

        
        return ProductResource::collection($products);
    }
}
