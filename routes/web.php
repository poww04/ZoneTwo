<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/register-admin', [AuthController::class, 'showRegisterAdmin'])->name('register-admin');
    Route::post('/register-admin', [AuthController::class, 'registerAdmin'])->name('register-admin.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::patch('/orders/{order}/approve', [AdminController::class, 'approveOrder'])->name('orders.approve');


});
