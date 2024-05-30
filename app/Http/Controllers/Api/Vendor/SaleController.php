<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
  
// is working
    // public function soldProducts(Request $request)
    // {
    //     $vendor = Auth::user()->vendor;
    //     $dayOfWeek = $request->input('day_of_week', null); // Sunday=0, Monday=1, ..., Saturday=6
    //     $month = $request->input('month', null); // January=1, February=2, ..., December=12
    //     $year = $request->input('year', null); // Year e.g., 2024
    
    //     // Log the vendor ID for debugging
    //     // Log::info('Vendor ID:', ['id' => $vendor->id]);
    
    //     // Query to get the top 3 highest selling products
    //     $productsQuery = Product::select(
    //         'products.id',
    //         'products.name',
    //         'products.photo', // Add photo to the select statement
    //         DB::raw('SUM(order_product.price * order_product.qty) as total_amount'),
    //         DB::raw('COUNT(order_product.id) as sales_count')
    //     )
    //         ->join('order_product', 'products.id', '=', 'order_product.product_id')
    //         ->join('orders', 'order_product.order_id', '=', 'orders.id')
    //         ->where('products.vendor_id', $vendor->id);
    
    //     if (!is_null($dayOfWeek)) {
    //         $productsQuery->whereRaw('DAYOFWEEK(orders.created_at) = ?', [$dayOfWeek + 1]);
    //     }
    
    //     if (!is_null($month)) {
    //         $productsQuery->whereRaw('MONTH(orders.created_at) = ?', [$month]);
    //     }
    
    //     if (!is_null($year)) {
    //         $productsQuery->whereRaw('YEAR(orders.created_at) = ?', [$year]);
    //     }
    
    //     // Log the constructed query
    //     // Log::info('Constructed Query:', ['query' => $productsQuery->toSql()]);
    
    //     $products = $productsQuery->groupBy('products.id', 'products.name', 'products.photo')
    //         ->orderBy('sales_count', 'desc')
    //         ->take(3)
    //         ->get();
    
    //     // Log the query results
    //     // Log::info('Query Results:', ['products' => $products]);
    
    //     // Prepare the data for each product by day of the week
    //     $productsData = [];
    
    //     foreach ($products as $product) {
    //         $dailyDataQuery = DB::table('order_product')
    //             ->select(
    //                 DB::raw('DAYOFWEEK(orders.created_at) as day_of_week'),
    //                 DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
    //             )
    //             ->join('orders', 'order_product.order_id', '=', 'orders.id')
    //             ->where('order_product.product_id', $product->id);
    
    //         if (!is_null($month)) {
    //             $dailyDataQuery->whereRaw('MONTH(orders.created_at) = ?', [$month]);
    //         }
    
    //         if (!is_null($year)) {
    //             $dailyDataQuery->whereRaw('YEAR(orders.created_at) = ?', [$year]);
    //         }
    
    //         $dailyData = $dailyDataQuery->groupBy(DB::raw('DAYOFWEEK(orders.created_at)'))
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
    //             'product_photo' => $product->photo,
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
        $month = $request->input('month', null); // January=1, February=2, ..., December=12
        $year = $request->input('year', null); // Year e.g., 2024
        $lastDays = $request->input('last_days', null); // Number of last days e.g., 7

        $productsQuery = Product::select(
            'products.id',
            'products.name',
            'products.photo', // Add photo to the select statement
            DB::raw('SUM(order_product.price * order_product.qty) as total_amount'),
            DB::raw('COUNT(order_product.id) as sales_count')
        )
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id);

        if (!is_null($dayOfWeek)) {
            $productsQuery->whereRaw('DAYOFWEEK(orders.created_at) = ?', [$dayOfWeek + 1]);
        }

        if (!is_null($month)) {
            $productsQuery->whereRaw('MONTH(orders.created_at) = ?', [$month]);
        }

        if (!is_null($year)) {
            $productsQuery->whereRaw('YEAR(orders.created_at) = ?', [$year]);
        }

        if (!is_null($lastDays)) {
            $productsQuery->where('orders.created_at', '>=', Carbon::now()->subDays($lastDays));
        }

        $products = $productsQuery->groupBy('products.id', 'products.name', 'products.photo')
            ->orderBy('sales_count', 'desc')
            ->take(3)
            ->get();

        $productsData = [];

        foreach ($products as $product) {
            $dailyDataQuery = DB::table('order_product')
                ->select(
                    DB::raw('DAYOFWEEK(orders.created_at) as day_of_week'),
                    DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
                )
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->where('order_product.product_id', $product->id);

            if (!is_null($month)) {
                $dailyDataQuery->whereRaw('MONTH(orders.created_at) = ?', [$month]);
            }

            if (!is_null($year)) {
                $dailyDataQuery->whereRaw('YEAR(orders.created_at) = ?', [$year]);
            }

            if (!is_null($lastDays)) {
                $dailyDataQuery->where('orders.created_at', '>=', Carbon::now()->subDays($lastDays));
            }

            $dailyData = $dailyDataQuery->groupBy(DB::raw('DAYOFWEEK(orders.created_at)'))
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
                'product_photo' => $product->photo,
                'total_amount' => $product->total_amount,
                'sales_count' => $product->sales_count,
                'daily_data' => $dailyData
            ];
        }

        return response()->json($productsData);
    }

    
    

// is working
    // public function topCategories(Request $request)
    // {
    //     $vendor = Auth::user()->vendor;
    //     $dayOfWeek = $request->input('day_of_week', null); // Sunday=0, Monday=1, ..., Saturday=6
    //     $month = $request->input('month', null); // January=1, February=2, ..., December=12
    //     $year = $request->input('year', null); // Year e.g., 2024
    
    //     // Query to get the top 4 highest selling categories
    //     $categoriesQuery = Category::select(
    //         'categories.id',
    //         'categories.name',
    //         DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
    //     )
    //         ->join('products', 'categories.id', '=', 'products.category_id')
    //         ->join('order_product', 'products.id', '=', 'order_product.product_id')
    //         ->join('orders', 'order_product.order_id', '=', 'orders.id')
    //         ->where('products.vendor_id', $vendor->id);
    
    //     if (!is_null($dayOfWeek)) {
    //         $categoriesQuery->whereRaw('DAYOFWEEK(orders.created_at) = ?', [$dayOfWeek + 1]);
    //     }
    
    //     if (!is_null($month)) {
    //         $categoriesQuery->whereRaw('MONTH(orders.created_at) = ?', [$month]);
    //     }
    
    //     if (!is_null($year)) {
    //         $categoriesQuery->whereRaw('YEAR(orders.created_at) = ?', [$year]);
    //     }
    
    //     $topCategories = $categoriesQuery->groupBy('categories.id', 'categories.name')
    //         ->orderBy('total_amount', 'desc')
    //         ->take(4)
    //         ->get();
    
    //     // Prepare the data for each category by month
    //     $categoriesData = [];
    
    //     foreach ($topCategories as $category) {
    //         $monthlyDataQuery = DB::table('categories')
    //             ->select(
    //                 DB::raw('MONTH(orders.created_at) as month'),
    //                 DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
    //             )
    //             ->join('products', 'categories.id', '=', 'products.category_id')
    //             ->join('order_product', 'products.id', '=', 'order_product.product_id')
    //             ->join('orders', 'order_product.order_id', '=', 'orders.id')
    //             ->where('categories.id', $category->id)
    //             ->where('products.vendor_id', $vendor->id);
    
    //         if (!is_null($dayOfWeek)) {
    //             $monthlyDataQuery->whereRaw('DAYOFWEEK(orders.created_at) = ?', [$dayOfWeek + 1]);
    //         }
    
    //         if (!is_null($month)) {
    //             $monthlyDataQuery->whereRaw('MONTH(orders.created_at) = ?', [$month]);
    //         }
    
    //         if (!is_null($year)) {
    //             $monthlyDataQuery->whereRaw('YEAR(orders.created_at) = ?', [$year]);
    //         }
    
    //         $monthlyData = $monthlyDataQuery->groupBy(DB::raw('MONTH(orders.created_at)'))
    //             ->orderBy(DB::raw('MONTH(orders.created_at)'))
    //             ->get()
    //             ->map(function($item) {
    //                 return [
    //                     'month' => $item->month,
    //                     'total_amount' => $item->total_amount
    //                 ];
    //             });
    
    //         $categoriesData[] = [
    //             'category_id' => $category->id,
    //             'category_name' => $category->name,
    //             'monthly_data' => $monthlyData
    //         ];
    //     }
    
    //     return response()->json($categoriesData);
    // }

    public function topCategories(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $dayOfWeek = $request->input('day_of_week', null); // Sunday=0, Monday=1, ..., Saturday=6
        $month = $request->input('month', null); // January=1, February=2, ..., December=12
        $year = $request->input('year', null); // Year e.g., 2024
        $lastDays = $request->input('last_days', null); // Number of last days e.g., 7
    
        // Query to get the top 4 highest selling categories
        $categoriesQuery = Category::select(
            'categories.id',
            'categories.name',
            DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
        )
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id);
    
        if (!is_null($dayOfWeek)) {
            $categoriesQuery->whereRaw('DAYOFWEEK(orders.created_at) = ?', [$dayOfWeek + 1]);
        }
    
        if (!is_null($month)) {
            $categoriesQuery->whereRaw('MONTH(orders.created_at) = ?', [$month]);
        }
    
        if (!is_null($year)) {
            $categoriesQuery->whereRaw('YEAR(orders.created_at) = ?', [$year]);
        }
    
        if (!is_null($lastDays)) {
            $categoriesQuery->where('orders.created_at', '>=', Carbon::now()->subDays($lastDays));
        }
    
        $topCategories = $categoriesQuery->groupBy('categories.id', 'categories.name')
            ->orderBy('total_amount', 'desc')
            ->take(4)
            ->get();
    
        // Prepare the data for each category by month
        $categoriesData = [];
    
        foreach ($topCategories as $category) {
            $monthlyDataQuery = DB::table('categories')
                ->select(
                    DB::raw('MONTH(orders.created_at) as month'),
                    DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
                )
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->join('order_product', 'products.id', '=', 'order_product.product_id')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->where('categories.id', $category->id)
                ->where('products.vendor_id', $vendor->id);
    
            if (!is_null($dayOfWeek)) {
                $monthlyDataQuery->whereRaw('DAYOFWEEK(orders.created_at) = ?', [$dayOfWeek + 1]);
            }
    
            if (!is_null($month)) {
                $monthlyDataQuery->whereRaw('MONTH(orders.created_at) = ?', [$month]);
            }
    
            if (!is_null($year)) {
                $monthlyDataQuery->whereRaw('YEAR(orders.created_at) = ?', [$year]);
            }
    
            if (!is_null($lastDays)) {
                $monthlyDataQuery->where('orders.created_at', '>=', Carbon::now()->subDays($lastDays));
            }
    
            $monthlyData = $monthlyDataQuery->groupBy(DB::raw('MONTH(orders.created_at)'))
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
    
    
   



    // is working
    // public function monthlyRevenue(Request $request)
    // {
    //     $vendor = Auth::user()->vendor;
    //     $monthsToShow = $request->input('months', 12); 
    //     $filterType = $request->input('filter', 'month'); // default to 'month'
    
    //     $startDate = now();
    //     $endDate = now();
    
    //     switch ($filterType) {
    //         case '24hours':
    //             $startDate = now()->subDay();
    //             break;
    //         case 'days':
    //             $daysToShow = $request->input('days', 30); // default to 30 days
    //             $startDate = now()->subDays($daysToShow);
    //             break;
    //         case 'month':
    //             $startDate = now()->subMonths($monthsToShow)->startOfMonth();
    //             $endDate = now()->endOfMonth();
    //             break;
    //         case 'year':
    //             $startDate = now()->startOfYear();
    //             $endDate = now()->endOfYear();
    //             break;
    //         default:
    //             $startDate = now()->subMonths($monthsToShow)->startOfMonth();
    //             $endDate = now()->endOfMonth();
    //             break;
    //     }
    
    //     $monthlyRevenue = DB::table('order_product')
    //         ->join('products', 'order_product.product_id', '=', 'products.id')
    //         ->join('orders', 'order_product.order_id', '=', 'orders.id')
    //         ->where('products.vendor_id', $vendor->id)
    //         ->whereBetween('orders.created_at', [$startDate, $endDate])
    //         ->select(
    //             DB::raw('YEAR(orders.created_at) as year'),
    //             DB::raw('MONTH(orders.created_at) as month'),
    //             DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
    //         )
    //         ->groupBy('year', 'month')
    //         ->orderBy('year')
    //         ->orderBy('month')
    //         ->get();
    
    //     $responseData = $monthlyRevenue->map(function($item) {
    //         return [
    //             'year' => $item->year,
    //             'month' => $item->month,
    //             'total_amount' => $item->total_amount,
    //         ];
    //     });
    
    //     return response()->json($responseData);
    // }

    public function monthlyRevenue(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $monthsToShow = $request->input('months', 12); 
        $filterType = $request->input('filter', 'month'); // default to 'month'

        $startDate = now();
        $endDate = now();

        switch ($filterType) {
            case '24hours':
                $startDate = now()->subDay();
                break;
            case 'days':
                $daysToShow = $request->input('days', 30); // default to 30 days
                $startDate = now()->subDays($daysToShow);
                break;
            case 'last7days':
                $startDate = now()->subDays(7);
                break;
            case 'month':
                $startDate = now()->subMonths($monthsToShow)->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            default:
                $startDate = now()->subMonths($monthsToShow)->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
        }

        $monthlyRevenue = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(orders.created_at) as year'),
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(order_product.price * order_product.qty) as total_amount')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $responseData = $monthlyRevenue->map(function($item) {
            return [
                'year' => $item->year,
                'month' => $item->month,
                'total_amount' => $item->total_amount,
            ];
        });

        return response()->json($responseData);
    }


    public function AllCategories(Request $request)
    {
        $vendor = Auth::user()->vendor;

        // Query to get categories with required details
        $categoriesQuery = Category::select(
            'categories.id',
            'categories.name',
            DB::raw('SUM(order_product.price * order_product.qty) as total_revenue'),
            DB::raw('COUNT(DISTINCT reviews.id) as total_ratings'),
            DB::raw('MIN(order_product.price) as min_price'),
            DB::raw('MAX(order_product.price) as max_price')
        )
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_product', 'products.id', '=', 'order_product.product_id')
            ->leftJoin('orders', 'order_product.order_id', '=', 'orders.id')
            ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
            ->where('products.vendor_id', $vendor->id)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        if ($categoriesQuery->isEmpty()) {
            return response()->json(['message' => 'No categories found.'], 404);
        }

        // Prepare the response data
        $categoriesData = [];

        foreach ($categoriesQuery  as $category) {
            $categoriesData[] = [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'total_revenue' => $category->total_revenue,
                'total_ratings' => $category->total_ratings,
                'price_range' => [
                    'min_price' => $category->min_price,
                    'max_price' => $category->max_price
                ]
            ];
        }

        return response()->json($categoriesData);
    }


    
}
