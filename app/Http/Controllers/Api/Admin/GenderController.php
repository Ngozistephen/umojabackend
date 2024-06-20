<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Gender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenderResource;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genders = Gender::all();
        return GenderResource::collection($genders);
    }

    public function allgenders()
    {
        $genders = Gender::orderBy('id', 'desc')->get();
        return GenderResource::collection($genders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
