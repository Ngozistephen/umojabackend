<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Post;
use App\Models\Vendor;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VendorResource;

class VendorPageController extends Controller
{
    public function vendors_article(Request $request, $vendorId)
    {
    
        $vendor = Vendor::findOrFail($vendorId);  
        $articles = Article::where('vendor_id', $vendor->id)->orderBy('created_at', 'desc')->paginate(10);
        return ArticleResource::collection($articles);
    }

    public function vendors_posts(Request $request, $vendorId)
    {   
      
        $vendor = Vendor::findOrFail($vendorId);
        
        $posts = Post::where('vendor_id', $vendor->id)
                     ->with('products')
                     ->orderBy('published_at', 'desc')
                     ->paginate(10);
        return PostResource::collection($posts);
    }
    public function vendors_promos(Request $request, $vendorId)
    {   
     
        $vendor = Vendor::findOrFail($vendorId);
        
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


    public function vendors_products(Request $request, $vendorId)
    {
      
        $vendor = Vendor::findOrFail($vendorId);

        $products = Product::where('vendor_id', $vendor->id)
                    ->with('variations')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return ProductResource::collection($products);
    }
    
    public function vendors_details(Request $request, $vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        return new VendorResource($vendor);
    }



}
