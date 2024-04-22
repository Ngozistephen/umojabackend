<?php

namespace App\Http\Controllers\Api;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;

class VendorController extends Controller
{
    public function show(Vendor $vendor)
    {
       
         return new VendorResource( $vendor);
        //  return response()->json($request->user());
    }

    // public function show(SubCategory $subCategory)
    // {
    //     $subCategory->load('category');
    //     $this->authorize('all-access');


    //     return new SubcategoryResource( $subCategory);
    // }



}
