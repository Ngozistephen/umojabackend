<?php

namespace App\Http\Controllers\Api\Auth;



use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\VendorSetupAccountMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorPasswordSetupMail;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\VendorRegistrationRequest;



class VendorRegisterController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required','confirmed', Password::defaults()],
            'terms_accepted' => ['required', 'accepted'],
        ]);
        $role = Role::where('name', 'Vendor')->value('id');

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'terms_accepted' => $request->terms_accepted,
            'role_id' => $role, 
        ]);

        $accountSetupToken = Str::random(60);
        $user->update(['password_setup_token' => $accountSetupToken]);

        // $accountSetupUrl = config('app.frontend_url') . '/vendor/setup/' . $user->id;
        $accountSetupUrl = config('app.frontend_url') . '/vendor/setup/' .  $accountSetupToken;
        Mail::to($user->email)->send(new VendorSetupAccountMail($user, $accountSetupUrl));
       


        $device = substr($request->userAgent() ?? '', 0, 255);
        $token = $user->createToken($device)->accessToken;

        $response = [
            'access_token' => $token,
            'vendor' => $user->first_name,
            'role' => Role::find($role)->name,
            'Message' => 'registered successfully. Check your email to set up your Account.'
        ];

        return response()->json($response,  Response::HTTP_CREATED);

    
    }
    // public function register(VendorRegistrationRequest $request)
    // {
    //     $userDetails = $request->only(['first_name', 'last_name', 'email', 'password','phone_number']);
    //     $role = Role::where('name', 'Vendor')->value('id');
    //     $userDetails['role_id'] = $role;

    //     return DB::transaction(function () use ($request, $userDetails, $role) {
    //         $user = User::create($userDetails);

    //         $uploadedFiles = $this->upload($request);
    //         $vendorData = $request->except(array_keys($uploadedFiles), ['password']);
    //         $vendor = Vendor::create(array_merge($vendorData, $uploadedFiles, ['user_id' => $user->id]));

    //         $passwordSetupToken = Str::random(60);
    //         $user->update(['password_setup_token' => $passwordSetupToken]);

  
    //         // $passwordSetupUrl = config('app.frontend_url') . '/auth/password_setup/' . $passwordSetupToken;

    //         Mail::to($user->email)->send(new VendorPasswordSetupMail($user, $passwordSetupUrl));

    //         $device = substr($request->userAgent() ?? '', 0, 255);
    //         $token = $user->createToken($device)->accessToken;

    //         $response = [
    //             'access_token' => $token,
    //             'vendor' => $user->first_name,
    //             'role' => Role::find($role)->name,
    //             'Message' => 'registered successfully. Check your email to set up your password.'
    //         ];

    //         return response()->json($response,  Response::HTTP_CREATED);
    //     });
    // }

    

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














}
