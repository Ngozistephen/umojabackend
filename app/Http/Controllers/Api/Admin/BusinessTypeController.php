<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\BusinessType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessTypeResource;

class BusinessTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businessTypes = BusinessType::all();
        return BusinessTypeResource::collection($businessTypes);
    }


    public function allbusinesstypes(Request $request)
    {
        $businessTypes = BusinessType::orderBy('id', 'desc')->get();

        return BusinessTypeResource::collection( $businessTypes);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);
        $businessType = auth()->user()->business_types()->create($request->validated());

        return new BusinessTypeResource($businessType);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $businessType = BusinessType::findOrFail($id);
        return new BusinessTypeResource($businessType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $businessType = BusinessType::findOrFail($id);
        $businessType->update($validatedData);

        return new BusinessTypeResource($businessType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $businessType = BusinessType::findOrFail($id);
        $businessType->delete();

        return response()->json(null, 204);
    }
}
