<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\ZoneRate;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ZoneRateResource;
use App\Http\Requests\StoreZoneRateRequest;
use App\Http\Requests\UpdateZoneRateRequest;

class ZoneRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShippingZone $shippingZone)
    {
        $zoneRates = $shippingZone->zoneRates()->get();
        return ZoneRateResource::collection($zoneRates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShippingZone $shippingZone, StoreZoneRateRequest $request)
    {
       
        $validatedData = $request->validated();
         $zoneRates = $shippingZone->zoneRates()->create($validatedData);
         return response()->json(['message' => 'Shipping zone created successfully', 'rates' => new ZoneRateResource($zoneRates)], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(ShippingZone $shippingZone, ZoneRate $zoneRate)
    {
        if ($zoneRate->shipping_zone_id !== $shippingZone->id) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        return new ZoneRateResource($zoneRate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShippingZone $shippingZone, UpdateZoneRateRequest $request, ZoneRate $zoneRate)
    {
        if ($zoneRate->shipping_zone_id !== $shippingZone->id) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        $validatedData = $request->validated();
        $zoneRate->update($validatedData);
        return response()->json(['message' => 'Shipping zone rate updated successfully', 'rates' => new ZoneRateResource($zoneRate)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingZone $shippingZone, ZoneRate $zoneRate)
    {
         if ($zoneRate->shipping_zone_id !== $shippingZone->id) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        $zoneRate->delete();
        return response()->json(['message' => 'Shipping zone rate deleted successfully'], 204);
    }
}
