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
   


   

 

    public function getBestSellingStores(Request $request)
    {
      
        $business_type_id = $request->query('business_type_id');

        
        $query = Vendor::select('vendors.*', DB::raw('COUNT(order_product.id) as orders_count'))
            ->leftJoin('order_product', 'vendors.id', '=', 'order_product.vendor_id')
            ->groupBy('vendors.id');

       
        if ($business_type_id !== null) {
            $query->where('vendors.business_type_id', $business_type_id);
        }
      
        $bestSellingStores = $query->orderByDesc('orders_count')
            ->take(10) 
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
            ->take(10)
            ->get();

        // Return the articles as a resource collection
        return ArticleResource::collection($latestArticles);
    }



    public function getMostSellingProducts()
    {
        
        $mostSellingProducts = Product::select('products.*', DB::raw('COUNT(order_product.product_id) as sales_count'))
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->groupBy('products.id')
            ->orderBy('sales_count', 'desc')
            ->take(10) 
            ->get();

        return ProductResource::collection($mostSellingProducts);
    }


    public function getProductsCompareAtPrice(Request $request)
    {
        // Query to fetch all products with compare_at_price
        $products = Product::with('vendor', 'variations')
            ->whereNotNull('compare_at_price')
            ->where('compare_at_price', '>', 0)
            ->latest()
            ->paginate(10);


        return ProductResource::collection($products);
    }
  
}
