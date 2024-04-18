<?php

namespace App\Http\Controllers\Api\Admin;


use Log;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubcategoryRequest;
use App\Http\Resources\SubcategoryResource;
use App\Http\Requests\UpdateSubcategoryRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $subCategories = SubCategory::with('category')->paginate(10);
        return SubcategoryResource::collection( $subCategories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubcategoryRequest $request)
    {
        $subCategory= SubCategory::create($request->validated());

        $uploadedFiles = $this->upload($request);
        return new SubcategoryResource( $subCategory);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $subCategory)
    {
        $subCategory->load('category');
        $this->authorize('all-access');


        return new SubcategoryResource( $subCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubCategory  $subCategory, UpdateSubcategoryRequest $request)
    {
        $subCategory->update($request->validated());

        return new SubcategoryResource( $subCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subCategory)
    {
        $this->authorize('all-access');
        $photoUrl = $subCategory->photo;
        // \Log::error("Failed to delete image from Cloudinary: " . $photoUrl );

        if ($photoUrl && !empty($photoUrl)) {
            $publicId = $this->getPublicIdFromCloudinaryUrl($photoUrl);
            if ($publicId) {
                try {
                  Cloudinary::destroy($publicId);
                } catch (\Exception $e) {
                  // Log the deletion error for debugging purposes
                  \Log::error("Failed to delete image from Cloudinary: " . $e->getMessage());
                  // You can also return a specific error response here if desired
                }
              }
        }

        $subCategory->delete();

        return response()->noContent();
    }
    // public function destroy(SubCategory $subCategory)
    // {
    //     $this->authorize('all-access');

    //     if ($subCategory->photo && !empty($subCategory->photo)) {
    //         // $publicId = Cloudinary::getPublicIdFromUrl($subCategory->photo);
    //         $publicId = $this->getPublicIdFromCloudinaryUrl($subCategory->photo);
    //         if ($publicId) {
    //             try {
    //                 $result = Cloudinary::destroy($publicId);
    //                 // Log the result of the deletion
    //                 Log::info('Cloudinary image deletion result', ['result' => $result]);
    //             } catch (\Exception $e) {
    //                 // Log or handle the error
    //                 \Log::error('Failed to delete image from Cloudinary', ['exception' => $e]);
    //                 return response()->json(['error' => 'Failed to delete image from Cloudinary'], 500);
    //             }
    //         }
    //     }

    //     $subCategory->delete();

    //     return response()->noContent();
    // }

     
    public function upload(Request $request)
    {
        // Specify the folder name where you want to upload the file
        $folder = 'sub_category_photo';
    
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            request()->validate([
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            // Upload the file to Cloudinary with folder specified
            $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
                'transformation' => [
                    ['width' => 400, 'height' => 400, 'crop' => 'fit'],
                    ['quality' => 'auto', 'fetch_format' => 'auto']
                ]
            ]);
    
            // Get the secure URL of the uploaded file
            $secureUrl = $cloudinaryResponse->getSecurePath();
            // Return the secure URL of the uploaded file
            return response()->json(['secure_url' => $secureUrl], 200);
        } else {
            // Handle case where no file is uploaded
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }


    private function getPublicIdFromCloudinaryUrl($url)
    {
        $parts = explode('/', $url);
        $publicIdWithExtension = end($parts);
        $publicId = pathinfo($publicIdWithExtension, PATHINFO_FILENAME);
        return $publicId;
    }
}
