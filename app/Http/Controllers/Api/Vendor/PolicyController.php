<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Policy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PolicyResource;
use App\Http\Requests\StorePolicyRequest;
use App\Http\Requests\UpdatePolicyRequest;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;
        $policy = $vendor->policy;

        return new PolicyResource($policy);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePolicyRequest  $request)
    {
        $vendor = Auth::user()->vendor;

        if ($vendor->policy) {
            return response()->json(['message' => 'Policy already exists for this vendor'], 400);
        }

        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;
        $policy = Policy::create($validatedData);

        return new PolicyResource($policy);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePolicyRequest  $request, Policy $policy)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $policy->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $policy->update($request->validated());

        return new PolicyResource($policy);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Policy $policy)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $policy->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $policy->delete();

        return response()->json(['message' => 'Policy deleted successfully']);
    }
}
