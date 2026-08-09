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
Route::get('/info-test-786', function () {
    phpinfo();
});

// Authentication endpoints
Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signup']);
// Route::post('/send-forgot-password-link', [UserController::class, 'sendForgotPasswordLink']);
// Route::post('/validate-reset-password-token', [UserController::class, 'validateResetPasswordToken']);
// Route::put('/reset-password-old', [UserController::class, 'resetPassword']);
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/reset-password', [UserController::class, 'resetPasswordViaOtp']);

// Public validation endpoints
Route::post('/does-email-exist', [UserController::class, 'doesEmailExist']);
Route::post('/does-phone-number-exist', [UserController::class, 'doesPhoneNumberExist']);
Route::post('/does-username-exist', [UserController::class, 'doesUsernameExist']);

// Stripe webhook (public)
Route::post('/stripe/webhook', [StripeWebHookController::class, 'handleWebhook']);

// Public metadata endpoints (for dropdowns, filters, etc.)
Route::get('/colours', [ColourController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/conditions', [ConditionController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/sizes', [SizeController::class, 'index']);
Route::get('/fulfillment-options', [FulfillmentOptionController::class, 'index']);
Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
Route::get('/shipping-service-providers', [ShippingServiceProviderController::class, 'index']);
Route::get('/order-statuses', [OrderController::class, 'getAllOrderStatuses']);


// ============================================
// AUTHENTICATED ROUTES (Require Login)
// ============================================

Route::middleware(['auth:api'])->group(function () {
    // Current user
    Route::get('/current-user', [UserController::class, 'getCurrentUser']);
    
    // Stripe connection
    Route::get('/stripe/connect', [UserController::class, 'stripeConnect']);
    Route::get('/stripe/connect/status', [UserController::class, 'stripeConnectStatus']);
    
    // Seller endpoints
    Route::get('/seller/info', [OrderController::class, 'getSellerInfo']);
    Route::get('/seller/orders', [OrderController::class, 'getSellerOrders']);
    Route::get('/seller/orders/{id}', [OrderController::class, 'getSellerOrderById']);
    Route::post('/seller/orders/{id}/status', [OrderController::class, 'addStatusToSellerOrder']);
    Route::post('/seller/orders/{id}/release-funds', [OrderController::class, 'releaseSellerOrderFunds']);
    Route::post('/seller/orders/{id}/deposit/release', [OrderController::class, 'releaseSellerOrderDeposit']);
    Route::post('/seller/orders/{id}/deposit/retain', [OrderController::class, 'retainSellerOrderDeposit']);
    Route::post('/seller/orders/{id}/deposit/dispute', [OrderController::class, 'disputeSellerOrderDeposit']);
    
    // Order management (authenticated)
    Route::post('/orders/{id}/sync-payment', [OrderController::class, 'syncStripePayment']);
    Route::apiResource('/orders', OrderController::class);
    
    // Product management (authenticated)
    Route::prefix('products')->group(function () {
        // Specific routes MUST come before wildcard routes
        Route::get('/my-products', [ProductController::class, 'getMyProducts']);
        Route::get('/favourite', [ProductController::class, 'favouriteProducts']);
        Route::post('/favourite/{id}', [ProductController::class, 'addFavourite']);
        Route::delete('/favourite/{id}', [ProductController::class, 'removeFavourite']);
        Route::get('/feature/{id}', [ProductController::class, 'featureThisItem']);
        
        // CRUD operations
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });

    // User profile & account management (authenticated)
    Route::prefix('user')->group(function () {
        Route::get('/profile', [UserController::class, 'getProfile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::put('/change-password', [UserController::class, 'changePassword']);
        Route::delete('/delete-account', [UserController::class, 'deleteAccount']);
        
        // Reviews (writing requires auth, reading is public)
        Route::post('/reviews', [UserController::class, 'addReview']);
    });

    // Validation endpoints (authenticated - for checking changes)
    Route::post('/does-phone-number-change-exist', [UserController::class, 'doesPhoneNumberChangeExist']);
    Route::post('/does-username-change-exist', [UserController::class, 'doesUsernameChangeExist']);
});

// ============================================
// PUBLIC PRODUCT ROUTES (Must be at the end to avoid conflicts with authenticated routes)
// ============================================
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product}', [ProductController::class, 'show']);
});


// Public user endpoints
Route::prefix('user')->group(function () {
    Route::get('/reviews/{sellerId}', [UserController::class, 'getReviews']);
    Route::get('/{id}', [UserController::class, 'findById']);
});
