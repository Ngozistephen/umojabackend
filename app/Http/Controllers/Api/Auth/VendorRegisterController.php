<?php

namespace App\Http\Controllers\Api\Auth;


use App\Models\Role;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\VendorRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRegistrationRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class VendorRegisterController extends Controller
{

    public function register(VendorRegistrationRequest $request)
    {
        $role = Role::where('name', 'Vendor')->value('id');
        $uploadedFiles = $this->upload($request);

        $vendorData = $request->except(array_keys($uploadedFiles));

        $vendor = Vendor::create(array_merge($vendorData, $uploadedFiles, ['role_id' => $role]));
 
        event(new VendorRegistered($vendor));

        $device = substr($request->userAgent() ?? '', 0, 255);

        $token = $vendor->createToken($device)->accessToken;

        $response = [
            'access_token' => $token,
            'user' => $vendor->first_name,
            'role' => Role::find($role)->name,
            'Message' => 'registered successfully.'
        ];

        return response()->json($response,  Response::HTTP_CREATED);
    }

    

// this one worked single
//     public function upload(Request $request)
// {
//     // Specify the folder name where you want to upload the file
//     $folder = 'business_image';

//     if ($request->hasFile('business_image')) {
//         $file = $request->file('business_image');
//         request()->validate([
//             'business_image' => 'required',
//             'business_image.*' => 'image|mimes:jpeg,png,JPG,jpg,gif,svg|max:6048'
//         ]);

//         // Upload the file to Cloudinary with folder specified
//         $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
//             'folder' => $folder,
//             'transformation' => [
//                 ['width' => 400, 'height' => 400, 'crop' => 'fit'],
//                 ['quality' => 'auto', 'fetch_format' => 'auto']
//             ]
//         ]);

//         // Get the secure URL of the uploaded file
//         $secureUrl = $cloudinaryResponse->getSecurePath();

//         // You can also log the Cloudinary upload response for debugging
//         \Log::info('Cloudinary upload response:', [
//             'public_id' => $cloudinaryResponse->getPublicId(),
//             'secure_url' => $cloudinaryResponse->getSecurePath(),
//             // Add more properties as needed
//         ]);


//         // Return the secure URL of the uploaded file
//         return response()->json(['secure_url' => $secureUrl], 200);
//     } else {
//         // Handle case where no file is uploaded
//         return response()->json(['error' => 'No file uploaded'], 400);
//     }
// }


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
