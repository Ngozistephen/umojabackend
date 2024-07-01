<?php

namespace App\Http\Controllers\Api\Auth;


use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class CustomerRegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required','confirmed', Password::defaults()],
            'terms_accepted' => ['required', 'accepted'],
        ]);

       
        $role = Role::where('name', 'Customer')->value('id');

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' =>  strtolower($request->email),
            'password' => Hash::make($request->password),
            'terms_accepted' => $request->terms_accepted,
            'role_id' => $role, 
        ]);

       
        event(new Registered($user));

        $device = substr($request->userAgent() ?? '', 0, 255);

        $token = $user->createToken($device)->accessToken;

        $response = [
            'access_token' => $token,
            'user' => $user->first_name,
            'role' => Role::find($role)->name,
            'Message' => 'registered successfully.'
        ];

        return response()->json($response,  Response::HTTP_CREATED);
    }
}
