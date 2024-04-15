<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Variation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VariationResource;
use App\Http\Requests\StoreVariationRequest;
use App\Http\Requests\UpdateVariationRequest;

class VariationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variations = Variation::all();
         return VariationResource::collection($variations);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariationRequest $request)
    {
        $variation = auth()->user()->variations()->create($request->validated());
     

        return new VariationResource($variation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Variation $variation)
    {
        $this->authorize('all-access');


        return new VariationResource($variation);
    }

  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariationRequest $request, Variation  $variation)
    {
        if ($variation->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
    
        $variation->update($request->validated());
    
        return new VariationResource($variation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variation $variation)
    {
        $this->authorize('all-access');

        if ($variation->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
 
        $variation->delete();
 
        return response()->noContent();
    }
}
