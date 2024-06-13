<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\ShippingZone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ShippingZoneResource;
use App\Http\Requests\StoreShippingZoneRequest;
use App\Http\Requests\UpdateShippingZoneRequest;

class ShippingZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;
        $shippingZone = ShippingZone::where('vendor_id', $vendor->id)->orderBy('created_at', 'desc')->paginate(10);
        $shippingZone->load('zoneRates');
        return ShippingZoneResource::collection($shippingZone);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingZoneRequest $request)
    {

        $vendor = Auth::user()->vendor;
       
        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id; 
        $shippingZone = auth()->user()->shippingZones()->create($validatedData);   

        return response()->json([
            'message' => 'Shipping Zone created successfully',
            'zone' => new ShippingZoneResource($shippingZone)
        ], 201);
      
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingZone $shippingZone)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $shippingZone->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $shippingZone->load('zoneRates');
        return new ShippingZoneResource($shippingZone);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingZoneRequest $request, ShippingZone $shippingZone)
    {  
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $shippingZone->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $shippingZone->update($request->validated());

        return response()->json(['message' => 'Shipping Zone updated successfully', 'zone' => new ShippingZoneResource($shippingZone)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingZone $shippingZone)
    {
       
        $vendor = Auth::user()->vendor;
        if ($vendor->id !==  $shippingZone->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $shippingZone->delete();

        return response()->json(['message' => 'Shipping Zone deleted successfully'], 204);
    }
}
