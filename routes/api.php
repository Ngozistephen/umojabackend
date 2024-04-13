<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorPasswordSetupMail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\Auth\VendorLoginController;
use App\Http\Controllers\Api\Auth\CustomerLoginController;
use App\Http\Controllers\Api\Auth\SocialRegisterController;
use App\Http\Controllers\Api\Auth\VendorRegisterController;
use App\Http\Controllers\Api\Auth\CustomerRegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('auth')->group(function () {
    Route::post('register_customer', [CustomerRegisterController::class, '__invoke']);
    Route::post('register_vendor', [VendorRegisterController::class, 'register']);
    Route::post('upload', [VendorRegisterController::class, 'upload']);
    Route::post('vendor_login', [VendorLoginController::class, '__invoke']);
    Route::post('customer_login', [CustomerLoginController::class, '__invoke']);
    // Route::post('forget_password', [ForgetPasswordController::class, '__invoke']);
    // Route::post('reset_password', [ResetPasswordController::class, '__invoke'])->name('auth.reset_password');
    // social registrations still be worked on for vendor registration part
    
    Route::get('{provider}/redirect', [SocialRegisterController::class, 'redirect']);
    Route::get('{provider}/callback', [SocialRegisterController::class, 'callback']);
    // Route::get('{provider}/redirect', [SocialLoginController::class, 'redirect']);
    // Route::get('{provider}/callback', [SocialLoginController::class, 'callback']);
    // the login endpoint for users has social login with with google and apple. separate login for user
    
});
