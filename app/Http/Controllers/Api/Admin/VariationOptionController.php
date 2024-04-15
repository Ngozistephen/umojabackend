<?php

namespace App\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\VariationsOption;
use App\Http\Controllers\Controller;
use App\Http\Resources\VariationOptionResource;
use App\Http\Requests\StoreVariationOptionRequest;
use App\Http\Requests\UpdateVariationOptionRequest;

class VariationOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variations_options = VariationsOption::all();
         return VariationOptionResource::collection($variations_options);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariationOptionRequest $request)
    {
        $variations_option = auth()->user()->variation_options()->create($request->validated());

        return new VariationOptionResource($variations_option);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VariationsOption $variations_option)
    {
         $this->authorize('all-access');

        return new VariationOptionResource($variations_option);

       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariationOptionRequest $request, VariationsOption $variations_option)
    {
        // dd(auth()->id());

        // dd($variations_option->user_id);

        if ($variations_option->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
    
        $variations_option->update($request->validated());

        return new VariationOptionResource($variations_option);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VariationsOption $variations_option)
    {
        $this->authorize('all-access');

        if ($variations_option->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
 
        $variations_option->delete();
 
        return response()->noContent();
    }
}
