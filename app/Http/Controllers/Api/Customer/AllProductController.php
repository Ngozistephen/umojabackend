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

            // filter by price range 
            ->when($request->priceFrom, function ($query) use ($request){
                $query->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->priceTo, function ($query) use ($request){
                $query->where('price', '<=', $request->priceTo * 100);
            })

            // for status is active, pass true in the param
            ->when($request->status === 'active', function ($query) use ($request){
                $query->where('status', 'active');
            })

            // for status is draft
            ->when($request->status === 'draft', function ($query) use ($request){
                $query->where('status', 'draft');
            })
            // filter by Archive 
            ->when($request->archive, function ($query) use ($request){
                $query->onlyTrashed();
            })

            // sortBy price and name
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request){
                    $query->orderBy($request->sortBy, $request->sortOrder);     
            })

            ->latest()
            ->get();
        return ProductResource::collection($products);
    }
}
