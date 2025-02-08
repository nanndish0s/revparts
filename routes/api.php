<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Public Routes (No Authentication Required)
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

// Public Product Routes
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);

// Add this route to proxy image requests
Route::get('/image-proxy/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        return response()->json(['error' => 'Image not found'], 404);
    }
    return response()->file($fullPath);
})->where('path', '.*');

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Logout route
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

    // Protected Product Routes
    Route::prefix('products')->group(function () {
        Route::post('/', [App\Http\Controllers\ProductController::class, 'store']); // Create a new product
        Route::get('/{id}', [App\Http\Controllers\ProductController::class, 'show']); // Get a specific product
        Route::put('/{id}', [App\Http\Controllers\ProductController::class, 'update']); // Update a product
        Route::delete('/{id}', [App\Http\Controllers\ProductController::class, 'destroy']); // Delete a product
    });

    // Cart Routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\CartController::class, 'index']); // Get cart items
        Route::post('/add', [App\Http\Controllers\Api\CartController::class, 'add']); // Add item to cart
        Route::put('/update/{cartItemId}', [App\Http\Controllers\Api\CartController::class, 'updateQuantity']); // Update cart item quantity
        Route::delete('/remove/{cartItemId}', [App\Http\Controllers\Api\CartController::class, 'remove']); // Remove item from cart
    });

    // Order Routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\OrderController::class, 'index']); // Get all orders
        Route::post('/', [App\Http\Controllers\Api\OrderController::class, 'store']); // Create new order
        Route::get('/{id}', [App\Http\Controllers\Api\OrderController::class, 'show']); // Get specific order
        Route::put('/{id}', [App\Http\Controllers\Api\OrderController::class, 'update']); // Update order status
    });

    // User-specific routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Add other protected routes here
    // Example: 
    // Route::get('/orders', [OrderController::class, 'index']);
    // Route::post('/orders', [OrderController::class, 'store']);
});
