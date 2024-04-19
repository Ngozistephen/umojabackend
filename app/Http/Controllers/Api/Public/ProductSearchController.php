<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductSearchResource;

class ProductSearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $productsQuery = Product::query()
            ->with(['variations'])
            ->when($request->search_global, function ($query) use ($request) {
                $searchTerm = '%' . $request->search_global . '%';
                $query->where(function ($q) use ($request, $searchTerm) {
                    $q->where('id', $request->search_global)
                        ->orWhere('name', 'like', $searchTerm)
                        ->orWhereHas('category', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', $searchTerm);
                        })
                        ->orWhereHas('subCategory', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', $searchTerm);
                        })
                        ->orWhere('price', 'like', $searchTerm)
                        ->orWhere('description', 'like', $searchTerm)
                        ->orWhere('sku', 'like', $searchTerm);
                });
            })->get();
            // ->latest()
            // ->paginate(10);

            return [
                'products' => ProductSearchResource::collection( $productsQuery)->response()->getData(true),
                // If you have other data to return, you can add it here
            ];
    }
}
