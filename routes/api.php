<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
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
    // Route::post('register_vendor', [VendorRegisterController::class, '__invoke']);
     Route::post('login', [LoginController::class, '__invoke']);
    // Route::post('forget_password', [ForgetPasswordController::class, '__invoke']);
    // Route::post('reset_password', [ResetPasswordController::class, '__invoke'])->name('auth.reset_password');
    // social registrations still be worked on for vendor registration part
    
    // Route::get('{provider}/redirect', [SocialLoginController::class, 'redirect']);
    // Route::get('{provider}/callback', [SocialLoginController::class, 'callback']);
    
});
