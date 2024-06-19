<?php

namespace App\Http\Controllers\Api\Public;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;

class HomePageController extends Controller
{
    public function bestSellingStores()
    {
        $topVendorsByCategory = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('vendors', 'order_product.vendor_id', '=', 'vendors.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id as category_id',
                'categories.name as category_name',
                'vendors.id as vendor_id',
                'vendors.business_name as vendor_name',
                DB::raw('SUM(order_product.qty * order_product.price) as total_sales')
            )
            ->groupBy('categories.id', 'vendors.id')
            ->orderBy('categories.id')
            ->orderByDesc('total_sales')
            ->get();

        $groupedByCategory = $topVendorsByCategory->groupBy('category_id');

        $filteredCategories = $groupedByCategory->filter(function ($vendors) {
            return $vendors->count() >= 7;
        });

        $bestSellingVendorsByCategory = [];

        foreach ($filteredCategories as $categoryId => $vendors) {
            $bestSellingVendorsByCategory[] = [
                'category_id' => $categoryId,
                'category_name' => $vendors->first()->category_name,
                'vendors' => VendorResource::collection($vendors->take(7)),
            ];
        }

        return response()->json($bestSellingVendorsByCategory);
    }
}
