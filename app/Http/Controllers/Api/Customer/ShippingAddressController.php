<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Models\ShippingAddress;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShippingAddressResource;
use App\Http\Requests\StoreShippingAddressRequest;
use App\Http\Requests\UpdateShippingAddressRequest;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shippingAddresses = ShippingAddress::with('user')
            ->where('user_id', auth()->id())
            ->paginate(10);
        return ShippingAddressResource::collection( $shippingAddresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingAddressRequest $request)
    {
        $shippingAddress = auth()->user()->shippingAddresses()->create($request->validated());

        return new ShippingAddressResource( $shippingAddress);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingAddress $shippingAddress)
    {
        $shippingAddress->load('user');
        return new ShippingAddressResource($shippingAddress);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingAddressRequest $request, ShippingAddress $shippingAddress)
    {
        $shippingAddress->update($request->validated());
        return new ShippingAddressResource($shippingAddress);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingAddress $shippingAddress)
    {
        $this->authorize('order-manage');

        $shippingAddress->delete();
        return response()->noContent();
    }
}
