<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Public\UserController;
use App\Http\Controllers\Api\Vendor\OrderController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Vendor\ProductController;
use App\Http\Controllers\Api\Admin\VariationController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\Auth\VendorLoginController;
use App\Http\Controllers\Api\Admin\SubcategoryController;
use App\Http\Controllers\Api\Customer\CheckoutController;
use App\Http\Controllers\Api\Admin\DiscountCodeController;
use App\Http\Controllers\Api\Auth\CustomerLoginController;
use App\Http\Controllers\Api\Auth\SocialRegisterController;
use App\Http\Controllers\Api\Auth\VendorRegisterController;
use App\Http\Controllers\Api\Customer\AllProductController;
use App\Http\Controllers\Api\Admin\ShippingMethodController;
use App\Http\Controllers\Api\Public\ProductSearchController;
use App\Http\Controllers\Api\Admin\VariationOptionController;
use App\Http\Controllers\Api\Auth\CustomerRegisterController;
use App\Http\Controllers\Api\Customer\PaymentMethodController;
use App\Http\Controllers\Api\Vendor\ProductVariationController;
use App\Http\Controllers\Api\Auth\ResetPasswordVendorController;
use App\Http\Controllers\Api\Auth\VendorPasswordSetupController;
use App\Http\Controllers\Api\Customer\ShippingAddressController;
use App\Http\Controllers\Api\Auth\ForgetVendorPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordCustomerController;
use App\Http\Controllers\Api\Auth\ForgetCustomerPasswordController;

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
    // Route::post('refresh_token_customer', [CustomerLoginController::class, 'refreshToken'])->middleware('auth:api');
    Route::post('logout', [LogoutController::class, '__invoke'])->middleware('auth:api');
    Route::post('password_setup/{token}', [VendorPasswordSetupController::class, '__invoke']);
    Route::post('forget_customer_password', [ForgetCustomerPasswordController::class, '__invoke']);
    Route::post('forget_vendor_password', [ForgetVendorPasswordController::class, '__invoke']);
    Route::post('reset_customer_password', [ResetPasswordCustomerController::class, '__invoke'])->name('auth.reset_customer_password');
    Route::post('reset_vendor_password', [ResetPasswordVendorController::class, '__invoke'])->name('auth.reset_vendor_password');
    // social registrations still be worked on for vendor registration part
    
    Route::get('{provider}/redirect', [SocialRegisterController::class, 'redirect']);
    Route::get('{provider}/callback', [SocialRegisterController::class, 'callback']);
    // Route::get('{provider}/redirect', [SocialLoginController::class, 'redirect']);
    // Route::get('{provider}/callback', [SocialLoginController::class, 'callback']);


    
    
});
Route::get('search', [ProductSearchController::class, '__invoke']);
Route::get('allproducts', [AllProductController::class, '__invoke']);
Route::middleware('auth:api')->group(function () {
    Route::get('users/{user}', [UserController::class, 'show']);
    // still working on the edit part 
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    Route::prefix('admin')->group(function () {
        Route::apiResource('categories', CategoryController::class);
        Route::post('categories/upload', [CategoryController::class, 'upload']);
        Route::apiResource('sub_categories', SubcategoryController::class);
        Route::get('sub_categories/category/{category_id}', [SubcategoryController::class, 'bycategory']);
        Route::post('sub_categories/upload', [SubcategoryController::class, 'upload']); 
        Route::apiResource('variations', VariationController::class);
        Route::apiResource('variations_option', VariationOptionController::class);
        Route::apiResource('discount_codes', DiscountCodeController::class);
        // the endpoint for admin is not ready yet, 
        Route::apiResource('shippingMethods', ShippingMethodController::class);

    });
    Route::prefix('vendor')->group(function () {
         Route::delete('products/{product_id}/delete_perm', [ProductController::class, 'delete_perm']);
         Route::put('products/{product_id}/restore', [ProductController::class, 'restore']);
         Route::apiResource('products', ProductController::class);
         Route::post('products/upload', [ProductController::class, 'upload']);
         Route::apiResource('products.variations', ProductVariationController::class)->except(['create', 'edit']);
         Route::post('/import/products', [ProductController::class, 'import']);
         Route::get('/export/products', [ProductController::class, 'export']);   
         Route::apiResource('orders', OrderController::class, );   
    });
    Route::prefix('customer')->group(function () {
        Route::post('products/{product}/addcart', [CartController::class, 'addCart']);
        Route::delete('products/{productId}/cartItems/{cartItemId}', [CartController::class, 'removecart']);
        Route::put('cartItems/{cartItem}', [CartController::class, 'updateCartItemQuantity']);
        Route::delete('cartItems/clear', [CartController::class, 'clearCart']);
        Route::get('cartItems', [CartController::class, 'getCartItems']);
        Route::post('apply_discount', [DiscountCodeController::class, 'applyDiscount']);
        Route::apiResource('shippingAddresses', ShippingAddressController::class);
        Route::apiResource('paymentMethods', PaymentMethodController::class);
        // still working on 
        Route::post('checkout', [CheckoutController::class, 'checkout']);
        Route::stripeWebhooks('webhook');
       
        

    });

   
});
