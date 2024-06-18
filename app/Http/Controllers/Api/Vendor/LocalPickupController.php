<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\LocalPickup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LocalPickupResource;
use App\Http\Requests\StoreLocalPickupRequest;
use App\Http\Requests\UpdateLocalPickupRequest;

class LocalPickupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $vendor = Auth::user()->vendor;
        $localPickups = LocalPickup::where('vendor_id', $vendor->id)->orderBy('created_at', 'desc')->paginate(10);
       
        return LocalPickupResource::collection($localDeliveries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocalPickupRequest $request)
    {
        $vendor = Auth::user()->vendor;
        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;
        $localPickup = LocalPickup::create($validatedData);

        return response()->json([
            'message' => 'Local Pickup created successfully',
            'delivery' => new LocalPickupResource($localPickup)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LocalPickup $localPickup)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $localPickup->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new LocalPickupResource($localPickup);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocalPickupRequest $request, LocalPickup $localPickup)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $localPickup->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validated();

        $localPickup->update($validatedData);

        return new LocalPickupResource($localPickup);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LocalPickup $localPickup)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $localPickup->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $localPickup->delete();

        return response()->json(['message' => 'Local pickup deleted successfully']);
    }
}
