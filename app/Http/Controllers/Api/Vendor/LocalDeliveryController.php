<?php

namespace App\Http\Controllers\Api\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LocalDeliveryResource;
use App\Http\Requests\StoreLocalDeliveryRequest;

class LocalDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;
        $localDeliveries = LocalDelivery::where('vendor_id', $vendor->id)->orderBy('created_at', 'desc')->paginate(10);
       
        return LocalDeliveryResource::collection($localDeliveries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocalDeliveryRequest $request)
    {
        $vendor = Auth::user()->vendor;
        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;
        $localDelivery = LocalDelivery::create($validatedData);

        return response()->json([
            'message' => 'Local delivery created successfully',
            'delivery' => new LocalDeliveryResource($localDelivery)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LocalDelivery $localDelivery)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $localDelivery->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new LocalDeliveryResource($localDelivery);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LocalDelivery $localDelivery)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $localDelivery->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            // Add your validation rules here
        ]);

        $localDelivery->update($validatedData);

        return new LocalDeliveryResource($localDelivery);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LocalDelivery $localDelivery)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $localDelivery->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $localDelivery->delete();

        return response()->json(['message' => 'Local delivery deleted successfully']);
    }
}
