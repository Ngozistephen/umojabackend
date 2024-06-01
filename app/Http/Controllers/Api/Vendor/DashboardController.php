<?php

namespace App\Http\Controllers\Api\Vendor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function weeklyRevenue(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $startDate = now()->subDays(6)->startOfDay(); // 6 days ago to get a full week including today
        $endDate = now()->endOfDay(); // End of today

        $weeklyRevenue = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DAYOFWEEK(orders.created_at) as day_of_week'),
                DB::raw('DAYNAME(orders.created_at) as day_name'),
                DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
            )
            ->groupBy('day_of_week', 'day_name')
            ->orderBy('day_of_week')
            ->get();

        $responseData = $weeklyRevenue->map(function($item) {
            return [
                'day_of_week' => $item->day_of_week,
                'day_name' => $item->day_name,
                'total_amount' => $item->total_amount,
            ];
        });

        return response()->json($responseData);
    }


    public function weeklyTotalRevenue(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $totalRevenue = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(DB::raw('SUM(order_product.price * order_product.qty) as total_amount'))
            ->first();

        return response()->json([
            'total_amount' => $totalRevenue->total_amount,
        ]);
    }


    public function weeklyTotalTransactions(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();


        $totalTransactions = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('orders.id')
            ->count('orders.id');

        return response()->json([
            'total_transactions' => $totalTransactions,
        ]);
    }

    public function weeklyTotalProductsSold(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $totalProductsSold = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_product.qty');


        return response()->json([
            'total_products_sold' => $totalProductsSold,
        ]);
    }

    public function topWeeklyTransactions(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $topTransactions = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'users.first_name as user_first_name',
                'users.last_name as user_last_name',
                'users.user_profile as user_profile_photo',
                'users.user_country as user_country',
                'products.name as product_name',
                'orders.created_at as transaction_date',
                DB::raw('order_product.price * order_product.qty as purchase_amount')
            )
            ->orderBy('purchase_amount', 'desc')
            ->limit(5) // Adjust the limit to the number of top transactions you need
            ->get();

        $responseData = $topTransactions->map(function($item) {
            return [
                'user_firstname' => $item->user_first_name,
                'user_lastname' => $item->user_last_name,
                'user_photo' => $item->user_profile_photo,
                'user_country' => $item->user_country,
                'product_name' => $item->product_name,
                'transaction_date' => $item->transaction_date,
                'purchase_amount' => $item->purchase_amount,
            ];
        });

        return response()->json($responseData);
    }

    public function recentWeeklyOrders(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $recentOrders = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.name as product_name',
                'categories.name as category_name',
                'order_product.qty as product_quantity',
                'order_product.price as sales_price',
                DB::raw('order_product.price * order_product.qty as total_price'),
                'orders.created_at as order_date'
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $responseData = $recentOrders->map(function($item) {
            return [
                'product_name' => $item->product_name,
                'category_name' => $item->category_name,
                'product_quantity' => $item->product_quantity,
                'sales_price' => $item->sales_price,
                'total_price' => $item->total_price,
                'order_date' => $item->order_date,
            ];
        });

        return response()->json($responseData);
    }





}
