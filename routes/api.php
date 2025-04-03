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
    StripeWebHookController
};
use Illuminate\Support\Facades\Route;
//for testing purposes
Route::post('/seed', [SeederController::class, 'seed']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signup']);

Route::post('/stripe/webhook', [StripeWebHookController::class, 'handleWebhook']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/stripe/connect', [UserController::class, 'stripeConnect']);
    Route::get('/products/favourite', [ProductController::class, 'favouriteProducts']);
    Route::post('/products/favourite/{id}', [ProductController::class, 'addFavourite']);
    Route::delete('/products/favourite/{id}', [ProductController::class, 'removeFavourite']);
    Route::apiResource('/products', ProductController::class);
    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/conditions', [ConditionController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/sizes', [SizeController::class, 'index']);
    Route::get('/fulfillmentoptions', [FulfillmentOptionController::class, 'index']);
    Route::get('/paymentmethods', [PaymentMethodController::class, 'index']);
    Route::apiResource('/orders', OrderController::class);
    Route::post('/user/reviews', [UserController::class, 'addReview']);
    Route::get('/user/reviews/{sellerId}', [UserController::class, 'getReviews']);
});
