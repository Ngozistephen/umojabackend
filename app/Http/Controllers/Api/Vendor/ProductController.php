<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductListRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductListRequest $request)
    {
        $products = Product::with('variations')
                    
                // Filter by ownership      
                ->where('user_id', auth()->id())

                // Search Global
                ->when($request->search_global, function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->where('id', $request->search_global)
                            ->orWhere('name', 'like', '%' . $request->search_global . '%')
                            ->orWhereHas('category', function ($q) use ($request) {
                                $q->where('name', 'like', '%' . $request->search_global . '%');
                            })
                            ->orWhereHas('subCategory', function ($q) use ($request) {
                                $q->where('name', 'like', '%' . $request->search_global . '%');
                            })
                            ->orWhere('price', 'like', '%' . $request->search_global . '%')
                            ->orWhere('description', 'like', '%' . $request->search_global . '%')
                            ->orWhere('sku', 'like', '%' . $request->search_global . '%');
                    });
                })

                // filter by price range 
                ->when($request->priceFrom, function ($query) use ($request){
                    $query->where('price', '>=', $request->priceFrom * 100);
                })
                ->when($request->priceTo, function ($query) use ($request){
                    $query->where('price', '<=', $request->priceTo * 100);
                })

                // for status is active, pass true in the param
                ->when($request->status === 'active', function ($query) use ($request){
                    $query->where('status', 'active');
                })

                // for status is draft
                ->when($request->status === 'draft', function ($query) use ($request){
                    $query->where('status', 'draft');
                })
                // filter by Archive 
                ->when($request->archive, function ($query) use ($request){
                    $query->onlyTrashed();
                })

                // sortBy price and name
                ->when($request->sortBy && $request->sortOrder, function ($query) use ($request){
                        $query->orderBy($request->sortBy, $request->sortOrder);     
                })

            ->latest()
            ->paginate(10);
        return ProductResource::collection($products);
    }

    

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(StoreProductRequest $request)
    // {
 
    //     $product = auth()->user()->products()->create($request->validated());

    //     $uploadedFiles = $this->upload($request);
    //     // if ($request->hasFile('photo')) {
            
    //     //     $product->addMultipleMediaFromRequest(['photo'])
    //     //         ->each(function ($fileAdder) use ($product) {
    //     //             $photo = $fileAdder->toMediaCollection('product_photo');

    //     //             $position = Media::query()
    //     //                 ->where('model_type', 'App\Models\Product')
    //     //                 ->where('model_id', $product->id)
    //     //                 ->max('position') + 1;

                
    //     //             $photo->update(['position' => $position]);
    //     //         });
    //     // }
    //     return response()->json(['message' => 'Product created successfully', 'product' => new ProductResource($product)], 201);
    // }

    public function store(StoreProductRequest $request)
    {
        // 1. Retrieve the vendor associated with the authenticated user
        $vendor = Auth::user()->vendor;

        // 2. Create the product using the retrieved vendor's ID
        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id; // Add the vendor_id to the validated data

        $product = auth()->user()->products()->create($validatedData);

        // 3. Upload any files if necessary
        $uploadedFiles = $this->upload($request);

        // 4. Return the response
        return response()->json([
            'message' => 'Product created successfully',
            'product' => new ProductResource($product)
        ], 201);
    }
    

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        $this->authorize('product-manage');

        if ($product->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
         $product->load('variations');
        return  new ProductResource($product);
    }


    public function showProduct(Product $product)
    {
        $product->load('variations');
        return new ProductResource($product);
    }

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($product->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
    
        $product->update($request->validated());
    
       
        return response()->json(['message' => 'Product updated successfully', 'product' => new ProductResource($product)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('product-manage');

        if ($product->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // if ($product->photo && !empty($product->photo)) {
        //     $publicId = Cloudinary::getPublicIdFromUrl($product->photo);
        //     if ($publicId) {
        //         Cloudinary::destroy($publicId);
        //     }
        // }
 
        $product->delete();
 
        return response()->json(['message' => 'Product Archived successfully'], 200);
    }


    public function restore($product_id)
    {
        $this->authorize('product-manage');

        $product = Product::withTrashed()->find($product_id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($product->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        if ($product->trashed()) {
            $product->restore();
            return response()->json(['message' => 'Product restored successfully'], 200);
        }
        return response()->json(['message' => 'Product is not archived, cannot be restored'], 400);
    }



    public function delete_perm($product_id)
    {
        $this->authorize('product-manage');

        $product = Product::find($product_id);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        if ($product->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $product->forceDelete();
    
        return response()->json(['message' => 'Product deleted permanently'], 200);
    }
    

    public function upload(Request $request)
    {
        $folder = 'product_photo';
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            request()->validate([
                'photo. *' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6048',
               
            ]);
    
            $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
          
            ]);
    
            $secureUrl = $cloudinaryResponse->getSecurePath();
            return response()->json(['secure_url' => $secureUrl], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }

//  working
    // public function upload(Request $request)
    // {
    //     $folder = 'product_photo'; // Change the folder name if needed

    //     if ($request->hasFile('photo')) {
    //         $file = $request->file('photo');
    //         request()->validate([
    //             'photo. *' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6048',
    //         ]);

    //         // Storing the image on Cloudinary
    //         $cloudinaryImage = $file->storeOnCloudinary($folder);

    //         // Retrieving the secure URL and public ID
    //         $secureUrl = $cloudinaryImage->getSecurePath();
    //         $publicId = $cloudinaryImage->getPublicId();

    //         return response()->json([
    //             'secure_url' => $secureUrl,
    //             'public_id' => $publicId
    //         ], 200);
    //     } else {
    //         return response()->json(['error' => 'No file uploaded'], 400);
    //     }
    // }

    // public function upload(Request $request)
    // {
    //     $folder = 'product_photo';
    
    //     if ($request->hasFile('photo')) {
    //         $file = $request->file('photo');
    //         request()->validate([
    //             'photo. *' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6048',
               
    //         ]);
    
    //         $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
    //             'folder' => $folder,
    //             // 'transformation' => [
    //             //     ['width' => 400, 'height' => 400, 'crop' => 'fit'],
    //             //     ['quality' => 'auto', 'fetch_format' => 'auto']
    //             // ]
    //         ]);
    
    //         $secureUrl = $cloudinaryResponse->getSecurePath();
    //         return response()->json(['secure_url' => $secureUrl], 200);
    //     } else {
    //         return response()->json(['error' => 'No file uploaded'], 400);
    //     }
    // }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        $file = $request->file('file');

        Excel::import(new ProductsImport, $file);

        return response()->json(['message' => 'Products imported successfully'], 200);
    }


    public function export()
    {
        $filename = 'Product_Import_' . now()->format('Ymd_His') . '.xlsx';

        Excel::store(new ProductsExport, $filename);

        $url = URL::to(Storage::url($filename));

        return response()->json(['url' => $url], 200);
    }
}
