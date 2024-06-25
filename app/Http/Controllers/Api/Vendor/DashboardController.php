<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\User;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;

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
            ->limit(5)
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
                'products.photo as product_photo',
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
                'product_photo' => $item->product_photo,
                'category_name' => $item->category_name,
                'product_quantity' => $item->product_quantity,
                'sales_price' => $item->sales_price,
                'total_price' => $item->total_price,
                'order_date' => $item->order_date,
            ];
        });

        return response()->json($responseData);
    }

    public function topWeeklyProducts(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $topProducts = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.name as product_name',
                'products.photo as product_photo',
                DB::raw('SUM(order_product.qty) as total_quantity_sold'),
                DB::raw('SUM(order_product.price * order_product.qty) as total_sales')
            )
            ->groupBy('products.id', 'products.name', 'products.photo')
            ->orderBy('total_sales', 'desc')
            ->limit(5) 
            ->get();

        $responseData = $topProducts->map(function($item) {
            return [
                'product_name' => $item->product_name,
                'product_photo' => $item->product_photo,
                'total_quantity_sold' => $item->total_quantity_sold,
                'total_sales' => $item->total_sales,
            ];
        });

        return response()->json($responseData);
    }


    public function weeklyOutOfStockProducts(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $outOfStockProducts = DB::table('products')
            ->where('user_id', $vendor->id)
            ->where('unit_per_item', '<=', 0)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->select('name', 'sku', 'photo', 'unit_per_item', 'price', 'updated_at')
            ->get();

        $responseData = $outOfStockProducts->map(function($item) {
            return [
                'product_name' => $item->name,
                'sku' => $item->sku,
                'product_photo' => $item->photo,
                'mini_stock' => $item->unit_per_item,
                'price' => $item->price,
                'updated_at' => $item->updated_at,
            ];
        });

        return response()->json($responseData);
    }

    public function weeklyOrdersByCountry(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $ordersByCountry = DB::table('orders')
            ->join('shipping_addresses', 'orders.shipping_address_id', '=', 'shipping_addresses.id')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'shipping_addresses.shipping_country',
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('shipping_addresses.shipping_country')
            ->get();

        $totalOrders = $ordersByCountry->sum('order_count');

        $responseData = $ordersByCountry->map(function($item) use ($totalOrders) {
            $percentage = ($totalOrders > 0) ? ($item->order_count / $totalOrders) * 100 : 0;
            return [
                'country' => $item->shipping_country,
                'order_count' => $item->order_count,
                'percentage' => round($percentage, 2),
            ];
        });

        return response()->json($responseData);
    }

    public function weeklyVendorTotalUsers(Request $request)
    {
        $vendor = Auth::user()->vendor;
    
        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();
    
        $totalUsers = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('orders.user_id')
            ->count('orders.user_id');
    
        return response()->json(['total_customers' => $totalUsers]);
    }


    public function vendorStats(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $filterType = $request->input('filter', 'last7days'); // default to 'last7days'
        $startDate = now();
        $endDate = now();

        switch ($filterType) {
            case 'last7days':
                $startDate = now()->subDays(7)->startOfDay();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $year = $request->input('year', now()->year);
                $startDate = Carbon::createFromDate($year)->startOfYear();
                $endDate = Carbon::createFromDate($year)->endOfYear();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->subDays(7)->startOfDay();
                $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();
                break;
            default:
                return response()->json(['error' => 'Invalid filter type'], 400);
        }

        // Total Revenue
        $totalRevenue = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(DB::raw('SUM(order_product.price * order_product.qty) as total_amount'))
            ->first();

        // Total Transactions
        $totalTransactions = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('orders.id')
            ->count('orders.id');

        // Total Products Sold
        $totalProductsSold = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_product.qty');

        // Total Users
        $totalUsers = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('orders.user_id')
            ->count('orders.user_id');

        return response()->json([
            'total_revenue' => $totalRevenue->total_amount,
            'total_transactions' => $totalTransactions,
            'total_products_sold' => $totalProductsSold,
            'total_customers' => $totalUsers,
        ]);
    }



    public function consolidatedVendorStats(Request $request)
    {
        // Retrieve vendor details
        $vendor = Auth::user()->vendor;

        // Determine the date range based on the filter type
        $filterType = $request->input('filter', 'last7days'); // default to 'last7days'
        $startDate = now();
        $endDate = now();

        switch ($filterType) {
            case 'last7days':
                $startDate = now()->subDays(7)->startOfDay();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $year = $request->input('year', now()->year);
                $startDate = Carbon::createFromDate($year)->startOfYear();
                $endDate = Carbon::createFromDate($year)->endOfYear();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->subDays(7)->startOfDay();
                $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();
                break;
            default:
                return response()->json(['error' => 'Invalid filter type'], 400);
        }

        // Get all followers
        $followers = $vendor->followers()->get();
        $followerIds = $followers->pluck('id')->toArray();

        // Get users who have ordered from the vendor within the date range
        $orderUserIds = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->where('order_product.vendor_id', $vendorId)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('orders.user_id')
            ->toArray();

        // Combine followers and order users to get all relevant users
        $allUserIds = array_unique(array_merge($followerIds, $orderUserIds));
        $allUsers = User::whereIn('id', $allUserIds)->get();

        // Retrieve order details for users who have ordered from the vendor within the date range
        $orders = Order::whereIn('user_id', $allUserIds)
            ->whereIn('id', function ($query) use ($vendorId, $startDate, $endDate) {
                $query->select('order_id')
                    ->from('order_product')
                    ->where('vendor_id', $vendorId)
                    ->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy('user_id');

        // Map users to their status and include order details if applicable
        $userStatus = $allUsers->map(function ($user) use ($orders) {
            $orderDetails = $orders->get($user->id) ?? [];
            $status = count($orderDetails) > 0 ? 'member' : 'following';
            return [
                'user' => new UserResource($user),
                'status' => $status,
                'orders' => OrderResource::collection($orderDetails),
            ];
        });

        // Total number of distinct users who follow the vendor or have ordered from the vendor
        $totalCustomer = count($allUserIds);

        // Total number of followers
        $totalFollowers = count($followerIds);

        // Number of followers active in the last 7 days
        $activeFollowers = $vendor->followers()
            ->where('last_active_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // Total number of users who have ordered from the vendor within the date range
        $totalOrderUsers = count($orderUserIds);

        // Weekly Revenue (or for the specified date range)
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

        $weeklyRevenueData = $weeklyRevenue->map(function ($item) {
            return [
                'day_of_week' => $item->day_of_week,
                'day_name' => $item->day_name,
                'total_amount' => $item->total_amount,
            ];
        });

        // Orders by Country within the date range
        $ordersByCountry = DB::table('orders')
            ->join('shipping_addresses', 'orders.shipping_address_id', '=', 'shipping_addresses.id')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'shipping_addresses.shipping_country',
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('shipping_addresses.shipping_country')
            ->get();

        $totalOrders = $ordersByCountry->sum('order_count');

        $ordersByCountryData = $ordersByCountry->map(function ($item) use ($totalOrders) {
            $percentage = ($totalOrders > 0) ? ($item->order_count / $totalOrders) * 100 : 0;
            return [
                'country' => $item->shipping_country,
                'order_count' => $item->order_count,
                'percentage' => round($percentage, 2),
            ];
        });

        // Vendor Statistics within the date range
        // Total Revenue
        $totalRevenue = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(DB::raw('SUM(order_product.price * order_product.qty) as total_amount'))
            ->first();

        // Total Transactions
        $totalTransactions = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('orders.id')
            ->count('orders.id');

        // Total Products Sold
        $totalProductsSold = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_product.qty');

        // Total Users
        $totalUsers = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('orders.user_id')
            ->count('orders.user_id');

        return response()->json([
            'total_customer' => $totalCustomer,
            'total_followers' => $totalFollowers,
            'active_followers' => $activeFollowers,
            'total_order_users' => $totalOrderUsers,
            'followers' => $userStatus,
            'weekly_revenue' => $weeklyRevenueData,
            'orders_by_country' => $ordersByCountryData,
            'total_revenue' => $totalRevenue->total_amount,
            'total_transactions' => $totalTransactions,
            'total_products_sold' => $totalProductsSold,
            'total_customers' => $totalUsers,
        ]);
    }







}
