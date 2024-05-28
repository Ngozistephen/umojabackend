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
    // public function __invoke(Request $request)
    // {
    //         $products = Product::with('variations')

    //         // Search Global
    //         ->when($request->search_global, function ($query) use ($request) {
    //             $query->where(function ($q) use ($request) {
    //                 $q->where('id', $request->search_global)
    //                     ->orWhere('name', 'like', '%' . $request->search_global . '%')
    //                     ->orWhereHas('category', function ($q) use ($request) {
    //                         $q->where('name', 'like', '%' . $request->search_global . '%');
    //                     })
    //                     ->orWhereHas('subCategory', function ($q) use ($request) {
    //                         $q->where('name', 'like', '%' . $request->search_global . '%');
    //                     })
    //                     ->orWhere('price', 'like', '%' . $request->search_global . '%')
    //                     ->orWhere('description', 'like', '%' . $request->search_global . '%')
    //                     ->orWhere('sku', 'like', '%' . $request->search_global . '%');
    //             });
    //         })

    //         // filter by price range 
    //         ->when($request->priceFrom, function ($query) use ($request){
    //             $query->where('price', '>=', $request->priceFrom * 100);
    //         })
    //         ->when($request->priceTo, function ($query) use ($request){
    //             $query->where('price', '<=', $request->priceTo * 100);
    //         })

    //         // for status is active, pass true in the param
    //         ->when($request->gender === 'active', function ($query) use ($request){
    //             $query->where('status', 'active');
    //         })
    //         ->when($request->category_name === 'active', function ($query) use ($request){
    //             $query->where('status', 'active');
    //         })
    //         ->when($request->sub_category_name === 'active', function ($query) use ($request){
    //             $query->where('status', 'active');
    //         })
    //         ->when($request->sizes === 'active', function ($query) use ($request){
    //             $query->where('status', 'active');
    //         })
    //         ->when($request->product_rating === 'active', function ($query) use ($request){
    //             $query->where('status', 'active');
    //         })
    //         ->when($request->compare_at_price === 'active', function ($query) use ($request){
    //             $query->where('status', 'active');
    //         })
    //         ->when($request->status === 'active', function ($query) use ($request){
    //             $query->where('status', 'active');
    //         })

    //         // for status is draft
    //         ->when($request->status === 'draft', function ($query) use ($request){
    //             $query->where('status', 'draft');
    //         })
    //         // filter by Archive 
    //         ->when($request->archive, function ($query) use ($request){
    //             $query->onlyTrashed();
    //         })

    //         // sortBy price and name
    //         ->when($request->sortBy && $request->sortOrder, function ($query) use ($request){
    //                 $query->orderBy($request->sortBy, $request->sortOrder);     
    //         })

    //         ->latest()
    //         ->paginate(60);
    //     return ProductResource::collection($products);
    // }

    public function __invoke(Request $request)
    {
        $products = Product::with('variations')

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
            ->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo * 100);
            })

            // Filter by gender
            ->when($request->gender, function ($query) use ($request) {
                $query->where('gender', $request->gender);
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

            // Filter by sizes
            ->when($request->sizes, function ($query) use ($request) {
                $query->whereHas('sizes', function ($q) use ($request) {
                    $q->where('name', $request->sizes);
                });
            })

            // Filter by product_rating
            ->when($request->product_rating, function ($query) use ($request) {
                $query->where('rating', '>=', $request->product_rating);
            })

            // Filter by compare_at_price
            ->when($request->compare_at_price, function ($query) use ($request) {
                $query->where('compare_at_price', '>=', $request->compare_at_price * 100);
            })

            // Filter by status
            // ->when($request->status, function ($query) use ($request) {
            //     $query->where('status', $request->status);
            // })

            // Filter by archive
            // ->when($request->archive, function ($query) {
            //     $query->onlyTrashed();
            // })

            // Sort by price and name
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })

            ->latest()
            ->paginate(60);

        return ProductResource::collection($products);
    }

}
