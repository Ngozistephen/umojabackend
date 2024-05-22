<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    // public function soldProducts(Request $request)
    // {
    //     $vendor = Auth::user()->vendor;
    //     $dayOfWeek = $request->input('day_of_week', null); // Sunday=0, Monday=1, ..., Saturday=6

    //     // Query to get the top 3 highest selling products
    //     $productsQuery = Product::select('products.id', 'products.name', DB::raw('SUM(order_product.price * order_product.qty) as total_amount'), DB::raw('COUNT(order_product.id) as sales_count'))
    //         ->join('order_product', 'products.id', '=', 'order_product.product_id')
    //         ->join('orders', 'order_product.order_id', '=', 'orders.id')
    //         ->where('products.user_id', $vendor->id);

    //     if (!is_null($dayOfWeek)) {
    //         $productsQuery->whereRaw('DAYOFWEEK(orders.created_at) = ?', [$dayOfWeek + 1]);
    //     }

    //     $products = $productsQuery->groupBy('products.id', 'products.name')
    //         ->orderBy('sales_count', 'desc')
    //         ->take(3)
    //         ->get();

    //     // Prepare the data for each product by day of the week
    //     $productsData = [];

    //     foreach ($products as $product) {
    //         $dailyData = DB::table('order_product')
    //             ->select(DB::raw('DAYOFWEEK(orders.created_at) as day_of_week'), DB::raw('SUM(order_product.price * order_product.quantity) as total_amount'))
    //             ->join('orders', 'order_product.order_id', '=', 'orders.id')
    //             ->where('order_product.product_id', $product->id)
    //             ->groupBy(DB::raw('DAYOFWEEK(orders.created_at)'))
    //             ->orderBy(DB::raw('DAYOFWEEK(orders.created_at)'))
    //             ->get()
    //             ->map(function($item) {
    //                 return [
    //                     'day_of_week' => $item->day_of_week,
    //                     'total_amount' => $item->total_amount
    //                 ];
    //             });

    //         $productsData[] = [
    //             'product_id' => $product->id,
    //             'product_name' => $product->name,
    //             'total_amount' => $product->total_amount,
    //             'sales_count' => $product->sales_count,
    //             'daily_data' => $dailyData
    //         ];
    //     }

    //     return response()->json($productsData);
    // }

    public function soldProducts(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $dayOfWeek = $request->input('day_of_week', null); // Sunday=0, Monday=1, ..., Saturday=6

        // Log the vendor ID for debugging
        Log::info('Vendor ID:', ['id' => $vendor->id]);

        // Query to get the top 3 highest selling products
        $productsQuery = Product::select(
            'products.id',
            'products.name',
            DB::raw('SUM(order_product.price * order_product.qty) as total_amount'),
            DB::raw('COUNT(order_product.id) as sales_count')
        )
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id);

        if (!is_null($dayOfWeek)) {
            $productsQuery->whereRaw('DAYOFWEEK(orders.created_at) = ?', [$dayOfWeek + 1]);
        }

        // Log the constructed query
        Log::info('Constructed Query:', ['query' => $productsQuery->toSql()]);

        $products = $productsQuery->groupBy('products.id', 'products.name')
            ->orderBy('sales_count', 'desc')
            ->take(3)
            ->get();

        // Log the query results
        Log::info('Query Results:', ['products' => $products]);

        // Prepare the data for each product by day of the week
        $productsData = [];

        foreach ($products as $product) {
            $dailyData = DB::table('order_product')
                ->select(
                    DB::raw('DAYOFWEEK(orders.created_at) as day_of_week'),
                    DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
                )
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->where('order_product.product_id', $product->id)
                ->groupBy(DB::raw('DAYOFWEEK(orders.created_at)'))
                ->orderBy(DB::raw('DAYOFWEEK(orders.created_at)'))
                ->get()
                ->map(function($item) {
                    return [
                        'day_of_week' => $item->day_of_week,
                        'total_amount' => $item->total_amount
                    ];
                });

            $productsData[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'total_amount' => $product->total_amount,
                'sales_count' => $product->sales_count,
                'daily_data' => $dailyData
            ];
        }

        return response()->json($productsData);
    }


    public function topCategories(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $topCategories = Category::select('categories.id', 'categories.name', DB::raw('SUM(order_product.price * order_product.qty) as total_amount'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_amount', 'desc')
            ->take(4)
            ->get();

        // Prepare the data for each category by month
        $categoriesData = [];

        foreach ($topCategories as $category) {
            $monthlyData = DB::table('categories')
                ->select(DB::raw('MONTH(orders.created_at) as month'), DB::raw('SUM(order_product.price * order_product.qty) as total_amount'))
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->join('order_product', 'products.id', '=', 'order_product.product_id')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->where('categories.id', $category->id)
                ->where('products.user_id', $vendor->id)
                ->groupBy(DB::raw('MONTH(orders.created_at)'))
                ->orderBy(DB::raw('MONTH(orders.created_at)'))
                ->get()
                ->map(function($item) {
                    return [
                        'month' => $item->month,
                        'total_amount' => $item->total_amount
                    ];
                });

            $categoriesData[] = [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'monthly_data' => $monthlyData
            ];
        }

        return response()->json($categoriesData);
    }
}
