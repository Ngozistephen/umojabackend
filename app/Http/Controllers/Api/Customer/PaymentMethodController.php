<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;


class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        $paymentMethods = PaymentMethod::with('user')
            ->where('user_id', auth()->id())
            ->paginate(10);

        return PaymentMethodResource::collection( $paymentMethods);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $paymentMethod = auth()->user()->paymentMethods()->create($request->validated());
        return new PaymentMethodResource($paymentMethod);
    }

 

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return new PaymentMethodResource($paymentMethod);
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update($request->validated());
        return new PaymentMethodResource( $paymentMethod);
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $this->authorize('order-manage');

        $paymentMethod->delete();
        return response()->noContent();
    }


}
