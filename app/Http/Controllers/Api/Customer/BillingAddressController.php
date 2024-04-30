<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBillingAddressRequest;
use App\Http\Requests\UpdateBillingAddressRequest;
use App\Http\Resources\BillingAddressResource;
use App\Models\BillingAddress;
use Illuminate\Http\Request;

class BillingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $billingAddresses = BillingAddress::with('user')
            ->where('user_id', auth()->id())
            ->paginate(10);

        return BillingAddressResource::collection($billingAddresses);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillingAddressRequest $request)
    {
        $billingAddress = auth()->user()->billingAddresses()->create($request->validated());
        return new BillingAddressResource($billingAddress);
    }

 

    /**
     * Display the specified resource.
     */
    public function show(BillingAddress $billingAddress)
    {
        return new BillingAddressResource($billingAddress);
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillingAddressRequest $request, BillingAddress $billingAddress)
    {
        $billingAddress->update($request->validated());
        return new BillingAddressResource($billingAddress);
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillingAddress $billingAddress)
    {
        $this->authorize('order-manage');

        $billingAddress->delete();
        return response()->noContent();
    }


}
