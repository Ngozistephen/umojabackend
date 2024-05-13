<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorProfileRequest;
use App\Http\Resources\VendorResource;
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
    // public function setupAccount(VendorProfileRequest $request, $token)
    // {
    //      // Fetch the user by the provided $userId
    //      $user = User::where('password_setup_token', $token)->first();
    //      if (! $user) {
    //          return response()->json(['error' => 'Invalid token'], 404);
    //         }
            
    //         $user->password_setup_token = null; 
    //         $vendorData = $request->validated();
    //         // Create or update the vendor profile
    //         $vendor = $user->vendor ?? new Vendor();
    //         $vendor->fill($vendorData);
    //         $user->vendor()->save($vendor);
    //      $uploadedFiles = $this->upload($request);


    //      return response()->json([
    //         'message' => 'Vendor profile Setup successfully',
    //         'user' => $user,
    //         'vendor' => $vendor,
    //         'uploadedFiles' => $uploadedFiles
    //     ], 200);



     
    // }


    public function setupAccount(VendorProfileRequest $request, $userId)
    {
      
        $user = User::findOrFail($userId);
      
        $vendor = $user->vendor ?? new Vendor();
        $vendor->fill($request->validated());
        $user->vendor()->save($vendor);

        // Process file uploads
        $uploadedFiles = $this->upload($request);
           


         return response()->json([
            'message' => 'Vendor profile Setup successfully',
            'user' => $user,
            'vendor' => new VendorResource($vendor),
            // 'uploadedFiles' => $uploadedFiles
        ], 200);



     
    }

    public function upload(Request $request)
    {
        $uploadedFiles = [];

        $fileFields = [
            'business_image' => 'business_image',
            'profile_photo' => 'profile_photo',
            'picture_vendor_id_number' => 'picture_vendor_id_number',
            'utility_photo' => 'utility_photo',
            'business_number_photo' => 'business_number_photo'
        ];

        foreach ($fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                $validatedData = $request->validate([
                    $field . '.*' => 'nullable|image|mimes:jpeg,png,JPG,jpg,gif,svg|max:6048',
                ], [
                    $field . '.*.image' => 'The ' . $field . ' must be an image.',
                    $field . '.*.mimes' => 'Unsupported file format for ' . $field . '. Supported formats are JPEG, PNG, GIF, and SVG.',
                    $field . '.*.max' => 'The ' . $field . ' may not be greater than 6 MB in size.',
                ]);

                $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
                    'folder' => $folder,
                    'transformation' => [
                        ['width' => 400, 'height' => 400, 'crop' => 'fit'],
                        ['quality' => 'auto', 'fetch_format' => 'auto']
                    ]
                ]);

                $secureUrl = $cloudinaryResponse->getSecurePath();

                $uploadedFiles[$field] = $secureUrl;
            }
        }

        return $uploadedFiles;
    }
}
