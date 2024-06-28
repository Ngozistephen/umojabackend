<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use App\Http\Requests\VendorProfileRequest;
use App\Http\Requests\UpdateVendorProfileRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function updateAccount(UpdateVendorProfileRequest $request, $userId)
    {
        
        $user = User::findOrFail($userId);
    
        $vendor = $user->vendor ?? new Vendor();
    
        $vendor->fill($request->validated());
    
        $user->vendor()->save($vendor);
    
        $uploadedFiles = [];
    
        if ($coverImageUrl = $this->uploadCoverImage($request)) {
            $uploadedFiles['cover_image'] = $coverImageUrl;
        }
    

        if ($additionalFiles = $this->upload($request)) {
            $uploadedFiles = array_merge($uploadedFiles, $additionalFiles);
        }
    
        // Return a JSON response with a success message and relevant data
        return response()->json([
            'message' => 'Vendor profile updated successfully',
            'user' => $user,
            'vendor' => new VendorResource($vendor),
            'uploadedFiles' => $uploadedFiles
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
   
    public function setupAccount(VendorProfileRequest $request, $userId)
    {
        $user = User::findOrFail($userId);

        $vendor = $user->vendor ?? new Vendor();
        $vendor->fill($request->validated());
        $user->vendor()->save($vendor);

        // Process cover image upload
        $coverImageUrl = $this->uploadCoverImage($request);

        // Process other file uploads
        $uploadedFiles = $this->upload($request);

        if ($coverImageUrl) {
            $uploadedFiles['cover_image'] = $coverImageUrl;
        }

        return response()->json([
            'message' => 'Vendor profile setup successfully',
            'user' => $user,
            'vendor' => new VendorResource($vendor),
            'uploadedFiles' => $uploadedFiles
        ], 200);
    }

    // public function setupAccount(VendorProfileRequest $request, $userId)
    // {
      
    //     $user = User::findOrFail($userId);
      
    //     $vendor = $user->vendor ?? new Vendor();
    //     $vendor->fill($request->validated());
    //     $user->vendor()->save($vendor);

    //     // Process file uploads
    //     $uploadedFiles = $this->upload($request);
           


    //      return response()->json([
    //         'message' => 'Vendor profile Setup successfully',
    //         'user' => $user,
    //         'vendor' => new VendorResource($vendor),
    //         // 'uploadedFiles' => $uploadedFiles
    //     ], 200);



     
    // }

    

    public function upload(Request $request)
    {
        $uploadedFiles = [];

        $fileFields = [
            'business_image' => 'business_image',
            'profile_photo' => 'profile_photo',
            'picture_vendor_id_number' => 'picture_vendor_id_number',
            'utility_photo' => 'utility_photo',
            'business_number_photo' => 'business_number_photo',
            // 'cover_image' => 'cover_image'
        ];

        foreach ($fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                $validatedData = $request->validate([
                    $field . '.*' => 'nullable|image|mimes:jpeg,png,JPG,jpg,gif,svg|max:5120',
                ], [
                    $field . '.*.image' => 'The ' . $field . ' must be an image.',
                    $field . '.*.mimes' => 'Unsupported file format for ' . $field . '. Supported formats are JPEG, PNG, GIF, and SVG.',
                    $field . '.*.max' => 'The ' . $field . ' may not be greater than 5 MB in size.',
                ]);

                $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
                    'folder' => $folder,
                    // 'transformation' => [
                    //     ['width' => 400, 'height' => 400, 'crop' => 'fit'],
                    //     ['quality' => 'auto', 'fetch_format' => 'auto']
                    // ]
                ]);

                $secureUrl = $cloudinaryResponse->getSecurePath();

                $uploadedFiles[$field] = $secureUrl;
            }
        }

        return $uploadedFiles;
    }

    public function uploadCoverImage(Request $request)
    {
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');

            // Validate the uploaded file
            $validatedData = $request->validate([
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6048', // Allowing larger size for high-resolution
            ], [
                'cover_image.image' => 'The cover image must be an image.',
                'cover_image.mimes' => 'Unsupported file format for cover image. Supported formats are JPEG, PNG, GIF, and SVG.',
                'cover_image.max' => 'The cover image may not be greater than 6 MB in size.', // Increased max size
            ]);

            // Upload the file to Cloudinary with higher resolution settings
            $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'cover_image',
                // 'transformation' => [
                //     ['width' => 1920, 'height' => 1080, 'crop' => 'fit'], // High resolution dimensions
                //     ['quality' => 'auto:best', 'fetch_format' => 'auto'] // Best quality setting
                // ]
            ]);

            $secureUrl = $cloudinaryResponse->getSecurePath();

            return ['cover_image' => $secureUrl];
        }

        return null;
    }


}
