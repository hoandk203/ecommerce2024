<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Routes công khai - không cần đăng nhập
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/products/{product}', [ProductController::class, 'show_normal'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'thaiviethoan'])->name('cart.add');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/updateAll', [CartController::class, 'updateAll'])->name('cart.updateAll');
Route::post('/cart/process', [CartController::class, 'process'])->name('cart.process');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/thank-you/{order}', [CartController::class, 'thankYou'])->name('cart.thank-you');

// Routes xác thực
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes cho user đã đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my-orders');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('my-orders.show');
});

// Routes cho admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/orders/{order}/print', [OrderController::class, 'printInvoice'])->name('orders.print-invoice');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});