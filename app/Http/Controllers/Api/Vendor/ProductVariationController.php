<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariationResource;
use App\Http\Requests\StoreProductVariationRequest;
use App\Http\Requests\UpdateProductVariationRequest;

class ProductVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $variations = $product->variations()->paginate(10);
        // return response()->json(['variations' => $variations], 200);
        return ProductVariationResource::collection($variations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Product $product, StoreProductVariationRequest $request)
    {
        // Validate the request
        $validatedData = $request->validated();

        // Create the product variation
        $variation = $product->variations()->create($validatedData);

        return response()->json(['message' => 'Product Variation created successfully', 'product_variation' => new ProductVariationResource($variation)], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product, ProductVariation $variation)
    {
        if ($product->id !== $variation->product_id) {
            return response()->json(['error' => 'This variation does not belong to the specified product.'], 404);
        }

        return new ProductVariationResource($variation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Product $product, ProductVariation $variation, UpdateProductVariationRequest $request)
    {
        if ($product->id !== $variation->product_id) {
            return response()->json(['error' => 'This variation does not belong to the specified product.'], 404);
        }

        $validatedData = $request->validated();

        $variation->update($validatedData);

        return response()->json(['message' => 'Product Variation updated successfully', 'product_variation' => new ProductVariationResource($variation)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductVariation $variation)
    {
        
        $this->authorize('product-manage');

        if ($product->id !== $variation->product_id) {
            return response()->json(['error' => 'This variation does not belong to the specified product.'], 404);
        }

        $variation->delete();

        return response()->json(['message' => 'Product Variation deleted successfully'], 200);
    }
}
