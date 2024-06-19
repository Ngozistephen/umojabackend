<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Vendor;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ProductResource;

class HomePageController extends Controller
{
   


    // public function getBestSellingStores()
    // {
    //     // Fetch vendors with the count of their orders
    //     $bestSellingStores = Vendor::select('vendors.*', DB::raw('COUNT(order_product.id) as orders_count'))
    //         ->join('order_product', 'vendors.id', '=', 'order_product.vendor_id')
    //         ->groupBy('vendors.id')
    //         ->orderBy('orders_count', 'desc')
    //         ->take(10) // Get top 10 best-selling stores
    //         ->get();

    //     return VendorResource::collection($bestSellingStores);
    // }


 


    public function getBestSellingStores($category_id = null)
    {
        // Base query to fetch vendors with the count of their orders
        $query = Vendor::select('vendors.*', DB::raw('COUNT(order_product.id) as orders_count'))
            ->leftJoin('order_product', 'vendors.id', '=', 'order_product.vendor_id')
            ->groupBy('vendors.id');

        // If a category_id filter is provided, add a where clause
        if ($category_id) {
            $query->whereExists(function ($subQuery) use ($category_id) {
                $subQuery->select(DB::raw(1))
                    ->from('order_product')
                    ->join('orders', 'order_product.order_id', '=', 'orders.id')
                    ->join('products', 'order_product.product_id', '=', 'products.id')
                    ->whereRaw('vendors.id = order_product.vendor_id')
                    ->where('products.category_id', $category_id);
            });
        }

        // Finalize the query by ordering and limiting the result
        $bestSellingStores = $query->orderByDesc('orders_count')
            ->take(10) // Get top 10 best-selling stores
            ->get();

        return VendorResource::collection($bestSellingStores);
    }

    
    public function homepopularProducts()
    {
        $popularProducts = Product::select('products.*')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->groupBy('products.id')
            ->orderByRaw('COUNT(order_product.product_id) DESC')
            ->take(10)
            ->get();
    
        return ProductResource::collection($popularProducts);
    }

 

    public function getLatestArticles()
    {
        // Fetch the latest articles from all vendors
        $latestArticles = Article::with('vendor', 'category')
            ->orderBy('published_at', 'desc')
            ->take(7)
            ->get();

        // Return the articles as a resource collection
        return ArticleResource::collection($latestArticles);
    }



    public function getMostSellingProducts()
    {
        // Fetch the most selling products
        $mostSellingProducts = Product::select('products.*', DB::raw('COUNT(order_product.product_id) as sales_count'))
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->groupBy('products.id')
            ->orderBy('sales_count', 'desc')
            ->take(10) 
            ->get();

        return ProductResource::collection($mostSellingProducts);
    }

  
}
