<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ChatController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// PASSWORD
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// UPDATE PROFILE
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);
});

// STORES
Route::get('/stores/nearby', [StoreController::class, 'nearby']);
Route::get('/stores/{id}', [StoreController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/stores', [StoreController::class, 'store']);
});

// PRODUCTS
Route::get('/stores/{id}/products', [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

// CART
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});

// ORDERS
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/{id}/accept', [OrderController::class, 'accept']);
    Route::post('/orders/{id}/complete', [OrderController::class, 'complete']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
});

// CHAT
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/conversations', [ChatController::class, 'createConversation']);
    Route::get('/conversations', [ChatController::class, 'index']);
    Route::get('/conversations/{id}/messages', [ChatController::class, 'getMessages']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);
});