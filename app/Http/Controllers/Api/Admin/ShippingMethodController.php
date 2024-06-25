<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShippingMethodResource;
use App\Http\Requests\StoreShippingMethodRequest;
use App\Http\Requests\UpdateShippingMethodRequest;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ShippingMethodResource::collection(ShippingMethod::all());
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingMethodRequest $request)
    {
        $vendor = Auth::user()->vendor;

        if ($vendor->shippingMethod) {
            return response()->json(['message' => 'Shipping Method already exists for this vendor'], 400);
        }

        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;
        $shippingMethod = ShippingMethod::create($validatedData);

        // $shippingMethod = auth()->user()->shippingMethods()->create($request->validated());

        return new ShippingMethodResource($shippingMethod);



    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingMethod  $shippingMethod)
    {
        return new ShippingMethodResource($shippingMethod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingMethodRequest $request, ShippingMethod $shippingMethod)
    {
        $shippingMethod->update($request->validated());
        return new ShippingMethodResource($shippingMethod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingMethod $shippingMethod)
    {
        $this->authorize('all-access');

        $shippingMethod->delete();
        return response()->noContent();
    }
}
