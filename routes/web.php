<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cartId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/{cartId}', [CartController::class, 'updateQuantity'])->name('cart.update');

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
});

// Public Product Routes
Route::get('/products', [UserProductController::class, 'index'])
    ->name('products.index');

Route::get('/products/{id}', [UserProductController::class, 'show'])
    ->name('products.show');

// Admin Routes - Using middleware directly
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
    
    Route::get('/admin/users', [AdminController::class, 'userManagement'])
        ->name('admin.user-management');
    
    Route::patch('/admin/users/{user}/update-role', [AdminController::class, 'updateUserRole'])
        ->name('admin.update-role');

    // Admin Order Routes
    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])
        ->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [OrderController::class, 'adminShow'])
        ->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('admin.orders.update-status');

    // Product Routes
    Route::get('/admin/products', [ProductController::class, 'adminIndex'])
        ->name('admin.products.index');
    
    Route::get('/admin/products/create', [ProductController::class, 'create'])
        ->name('admin.products.create');
    
    Route::post('/admin/products', [ProductController::class, 'store'])
        ->name('admin.products.store');
    
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])
        ->name('admin.products.edit');
    
    Route::put('/admin/products/{id}', [ProductController::class, 'update'])
        ->name('admin.products.update');
    
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])
        ->name('admin.products.destroy');
});

Route::get('/debug/images', function () {
    return view('debug.image_test');
});

require __DIR__.'/auth.php';
