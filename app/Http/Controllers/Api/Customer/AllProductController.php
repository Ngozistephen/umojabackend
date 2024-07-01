<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class AllProductController extends Controller
{
    /**
     * Handle the incoming request.
     */


    public function __invoke(Request $request)
    {
        $products = Product::with('variations', 'category', 'subCategory', 'user.vendor', 'reviews', 'gender')

            // Search Global
            ->when($request->search_global, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('id', $request->search_global)
                        ->orWhere('name', 'like', '%' . $request->search_global . '%')
                        ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search_global . '%');
                        })
                        ->orWhereHas('subCategory', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search_global . '%');
                        })
                        ->orWhere('price', 'like', '%' . $request->search_global . '%')
                        ->orWhere('description', 'like', '%' . $request->search_global . '%')
                        ->orWhere('sku', 'like', '%' . $request->search_global . '%');
                });
            })

            // Filter by price range
            ->when($request->priceMinimum, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceMinimum );
            })
            ->when($request->priceMaximum, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceMaximum );
            })

            // Filter by gender
            ->when($request->gender, function ($query) use ($request) {
                $query->whereHas('gender', function ($q) use ($request) {
                    if ($request->gender == 'male') {
                        $q->whereIn('name', ['male', 'unisex']);
                    } elseif ($request->gender == 'female') {
                        $q->whereIn('name', ['female', 'unisex']);
                    } else {
                        $q->where('name', $request->gender);
                    }
                });
            })

            // Filter by category_name
            ->when($request->category_name, function ($query) use ($request) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('name', $request->category_name);
                });
            })

            // Filter by sub_category_name
            ->when($request->sub_category_name, function ($query) use ($request) {
                $query->whereHas('subCategory', function ($q) use ($request) {
                    $q->where('name', $request->sub_category_name);
                });
            })

            // ->when($request->sub_category_name, function ($query) use ($request) {
            //     $query->whereHas('subCategory', function ($q) use ($request) {
            //         $q->where(function ($query) use ($request) {
            //             $query->where('name', $request->sub_category_name);
                                
            //         });
            //     });
            // })

            // Filter by sizes
            ->when($request->sizes, function ($query) use ($request) {
                $query->whereJsonContains('sizes', $request->sizes);
            })

            // Filter by product_rating
            ->when($request->product_rating, function ($query) use ($request) {
                $query->whereHas('reviews', function ($q) use ($request) {
                    $q->where('rating', '>=', $request->product_rating);
                });
            })

            // Filter by compare_at_price
            // ->when($request->compare_at_price, function ($query) use ($request) {
            //     $query->where('compare_at_price', '>=', $request->compare_at_price * 100);
            // })
            ->when($request->compare_at_price, function ($query) use ($request) {
                // Convert percentage to actual value
                $percentage = $request->compare_at_price / 100;
                // Apply filter based on the calculated compare_at_price
                $query->whereRaw('(price + (price * ?)) >= compare_at_price', [$percentage]);
            })

            // Filter by status
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })

            // Filter by archive
            ->when($request->archive, function ($query) {
                $query->onlyTrashed();
            })

            // Sort by price and name
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })

            ->latest()
            ->paginate(12);

        return ProductResource::collection($products);
    }


}
