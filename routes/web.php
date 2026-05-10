<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SavedController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

//Products
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('detail');

// Saved / Wishlist
Route::get('/saved', [SavedController::class, 'index'])->name('saved');
Route::post('/saved/{product}/toggle', [SavedController::class, 'toggle'])->name('saved.toggle');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{product}/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/{id}/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout
Route::get('/cart/shipping',[OrderController::class, 'shipping'])->name('cart.shipping');
Route::post('/cart/shipping', [OrderController::class, 'processShipping'])->name('cart.shipping.process');
Route::get('/cart/payment', [OrderController::class, 'payment'])->name('orders.payment');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

// Guest
Route::post('/orders/{order}/claim', [OrderController::class, 'claimOrder'])->name('orders.claim');

// Auth
Route::get('/login',[AuthController::class, 'showLogin'])->name('login');
Route::post('/login',[AuthController::class, 'login']);
Route::get('/register',[AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer account
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');
});

// Admin panel
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('add');
    Route::post('/products', [AdminProductController::class, 'store'])->name('store');
    Route::get('/products/{product}', [AdminProductController::class, 'edit'])->name('detail');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
});
