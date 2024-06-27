<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Models\AdminShipping;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminShippingResource;

class AdminShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AdminShippingResource::collection(AdminShipping::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $adminShipping = AdminShipping::create($request->all());
        return new AdminShippingResource($adminShipping);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminShipping $adminShipping)
    {
        return new AdminShippingResource($adminShipping);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdminShipping $adminShipping)
    {
        $adminShipping->update($request->all());
        return new AdminShippingResource($adminShipping);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminShipping $adminShipping)
    {
        $adminShipping->delete();
        return response()->json(null, 204);
    }
}
