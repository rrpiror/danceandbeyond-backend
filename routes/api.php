<?php

use App\Http\Controllers\{
    BrandController,
    CategoryController,
    ConditionController,
    FulfillmentOptionController,
    OrderController,
    PaymentMethodController,
    ProductController,
    SizeController,
    UserController,
    SeederController,
    StripeWebHookController,
    ShippingServiceProviderController,
    ColourController
};
use Illuminate\Support\Facades\Route;
//for testing purposes
Route::post('/seed', [SeederController::class, 'seed']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/send-forgot-password-link', [UserController::class, 'sendForgotPasswordLink']);
Route::post('/validate-reset-password-token', [UserController::class, 'validateResetPasswordToken']);
Route::put('/reset-password', [UserController::class, 'resetPassword']);

Route::post('/stripe/webhook', [StripeWebHookController::class, 'handleWebhook']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/current-user', [UserController::class, 'getCurrentUser']);
    Route::get('/stripe/connect', [UserController::class, 'stripeConnect']);
    Route::get('/seller/info', [OrderController::class, 'getSellerInfo']);
    Route::get('/seller/orders', [OrderController::class, 'getSellerOrders']);
    Route::get('/seller/orders/{id}', [OrderController::class, 'getSellerOrderById']);
    Route::post('/seller/orders/{id}/status', [OrderController::class, 'addStatusToSellerOrder']);
    Route::get('/order-statuses', [OrderController::class, 'getAllOrderStatuses']);
    Route::get('/colours', [ColourController::class, 'index']);
    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/conditions', [ConditionController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/sizes', [SizeController::class, 'index']);
    Route::get('/fulfillment-options', [FulfillmentOptionController::class, 'index']);
    Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
    Route::apiResource('/orders', OrderController::class);
    Route::get('/shipping-service-providers', [ShippingServiceProviderController::class, 'index']);
    
    Route::prefix('products')->group(function () {
        Route::get('/favourite', [ProductController::class, 'favouriteProducts']);
        Route::post('/favourite/{id}', [ProductController::class, 'addFavourite']);
        Route::delete('/favourite/{id}', [ProductController::class, 'removeFavourite']);
        Route::get('/my-products', [ProductController::class, 'getProductsByUserId']);
        Route::get('/feature/{id}', [ProductController::class, 'featureThisItem']);
        Route::apiResource('/', ProductController::class)->parameters(['' => 'product']);
    });

    Route::prefix('user')->group(function () {
        Route::post('/reviews', [UserController::class, 'addReview']);
        Route::get('/reviews/{sellerId}', [UserController::class, 'getReviews']);
        Route::put('/change-password', [UserController::class, 'changePassword']);
        Route::delete('/delete-account', [UserController::class, 'deleteAccount']);
        Route::get('/profile', [UserController::class, 'getProfile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::get('/{id}', [UserController::class, 'findById']);
    });
});
