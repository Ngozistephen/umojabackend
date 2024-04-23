<?php

namespace App\Http\Controllers\Api\Auth;


use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorPasswordSetupMail;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\VendorRegistrationRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class VendorRegisterController extends Controller
{

    public function register(VendorRegistrationRequest $request)
    {
       
        $userDetails = $request->only(['first_name', 'last_name', 'email', 'password','phone_number']);
        $role = Role::where('name', 'Vendor')->value('id');
        $userDetails['role_id'] = $role;

        $user = User::create($userDetails);

        $uploadedFiles = $this->upload($request);
        $vendorData = $request->except(array_keys($uploadedFiles), ['password']);
        $vendor = Vendor::create(array_merge($vendorData, $uploadedFiles, ['user_id' => $user->id]));


        $passwordSetupToken = Str::random(60);
        $user->update(['password_setup_token' => $passwordSetupToken]);

        $passwordSetupUrl = config('app.frontend_url') . '/vendor/setpass/' . $passwordSetupToken;
        // $passwordSetupUrl = config('app.frontend_url') . '/auth/password_setup/' . $passwordSetupToken;

        Mail::to($user->email)->send(new VendorPasswordSetupMail($user, $passwordSetupUrl));

        $device = substr($request->userAgent() ?? '', 0, 255);
        $token = $user->createToken($device)->accessToken;

        $response = [
            'access_token' => $token,
            'vendor' => $user->first_name,
            'role' => Role::find($role)->name,
            'Message' => 'registered successfully. check your mail to Setup your password'
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
