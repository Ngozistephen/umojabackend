<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

        $existingShippingMethod = ShippingMethod::where('vendor_id', $vendor->id)->first();

        if ($existingShippingMethod) {
            return response()->json(['error' => 'Shipping method already exists. Please update the existing shipping method.'], 400);
        }

        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;
        $shippingMethod = new ShippingMethod();
        $shippingMethod->vendor_id = $vendor->id;
        $shippingMethod->name = $request->name;
        $shippingMethod->admin_shipping_id = $request->admin_shipping_id;

        $shippingMethod->save();

        // $shippingMethod = auth()->user()->shippingMethods()->create($request->validated());

        return response()->json(['message' => 'Shipping method created successfully', 'shipping_method' => $shippingMethod], 201);



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
    public function update(UpdateShippingMethodRequest $request, $id)
    {
        $vendor = Auth::user()->vendor;
    
      
        $shippingMethod = ShippingMethod::where('vendor_id', $vendor->id)->where('id', $id)->first();
    
        if (!$shippingMethod) {
            return response()->json(['error' => 'Shipping method not found or does not belong to the vendor.'], 404);
        }
    
      
        $validatedData = $request->validated();
    
       
        if (isset($validatedData['name'])) {
            $shippingMethod->name = $validatedData['name'];
        }
        if (isset($validatedData['admin_shipping_id'])) {
            $shippingMethod->admin_shipping_id = $validatedData['admin_shipping_id'];
        }
    
        // Add other fields as necessary
        $shippingMethod->save();
    
        return response()->json(['message' => 'Shipping method updated successfully', 'shipping_method' => $shippingMethod], 200);
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
