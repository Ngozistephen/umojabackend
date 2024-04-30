<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountCodeResource;
use App\Http\Requests\StoreDiscountCodeRequest;
use App\Http\Requests\UpdateDiscountCodeRequest;

class DiscountCodeController extends Controller
{


    public function index()
    {
        $discountCodes = DiscountCode::all();

        return DiscountCodeResource::collection($discountCodes);
    }

    public function store(StoreDiscountCodeRequest $request)
    {

        $discountCode = DiscountCode::create($request->validated());

        return new DiscountCodeResource ($discountCode);
    }


    public function show(DiscountCode  $discountCode )
    {
        $this->authorize('all-access');
        return new DiscountCodeResource($discountCode);
    }

    public function update(UpdateDiscountCodeRequest $request, DiscountCode  $discountCode)
    {
        $discountCode->update($request->validated());
    
        return new DiscountCodeResource($discountCode);
    }


    public function destroy(DiscountCode  $discountCode)
    {
        $this->authorize('all-access');

        $discountCode->delete();
 
        return response()->noContent();
    }

    public function applyDiscount(Request $request)
{
    $request->validate([
        'code' => 'required|exists:discount_codes,code',
    ]);

    $discountCode = DiscountCode::where('code', $request->code)->first();

    // Check if the discount code is valid
    if (!$this->isValidDiscountCode($discountCode)) {
        return response()->json(['error' => 'Discount code is not valid or has expired'], 400);
    }

    // Apply the discount to the order total
    $discountedAmount = $this->calculateDiscountedAmount($discountCode, $request->orderTotal);

    // Return the discounted amount or apply it to the order
    return response()->json(['discounted_amount' => $discountedAmount]);
}

// private function isValidDiscountCode($discountCode)
// {
//     if (!$discountCode) {
//         return false;
//     }

//     $now = now();

//     // Check if the discount code is within the valid date range
//     if ($discountCode->valid_from && $now->lt($discountCode->valid_from)) {
//         return false;
//     }

//     if ($discountCode->valid_to && $now->gt($discountCode->valid_to)) {
//         return false;
//     }

//     // Check if the usage limit has been reached
//     if ($discountCode->usage_limit && $discountCode->usage_limit <= $discountCode->usage_count) {
//         return false;
//     }

//     return true;
// }



private function isValidDiscountCode($discountCode)
{
    if (!$discountCode) {
        return false;
    }

    $now = now();
    Log::info("Current date: " . $now);
    Log::info("Valid from: " . $discountCode->valid_from);
    Log::info("Valid to: " . $discountCode->valid_to);
    Log::info("Usage limit: " . $discountCode->usage_limit);
    Log::info("Usage count: " . $discountCode->usage_count);

    // Check if the discount code is within the valid date range
    if ($discountCode->valid_from && $now->lt($discountCode->valid_from)) {
        return false;
    }

    if ($discountCode->valid_to && $now->gt($discountCode->valid_to)) {
        return false;
    }

    // Check if the usage limit has been reached
    if ($discountCode->usage_limit && $discountCode->usage_limit <= $discountCode->usage_count) {
        return false;
    }

    return true;
}


private function calculateDiscountedAmount($discountCode, $orderTotal)
{
    // Check if the discount code is fixed amount type
    if ($discountCode->discount_type === 'fixed_amount') {
        // If it's fixed amount, subtract the discount amount from the order total
        $discountedAmount = $orderTotal - $discountCode->discount_amount;
        
        // Ensure the discounted amount doesn't go below zero
        $discountedAmount = max(0, $discountedAmount);
        
        // Update the usage count for the discount code
        $discountCode->increment('usage_count');
        
        return $discountedAmount;
    }
}

}
