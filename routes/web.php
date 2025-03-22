<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BuyerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/seller', [DashboardController::class, 'sellerDashboard'])->name('dashboard.seller');

    Route::get('/seller/products', [SellerController::class, 'index'])->name('seller.products');
    Route::post('/seller/products', [SellerController::class, 'store'])->name('seller.products.store');
    Route::put('/seller/products/{id}', [SellerController::class, 'update'])->name('seller.products.update');
    Route::delete('/seller/products/{id}', [SellerController::class, 'destroy'])->name('seller.products.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/buyer', [BuyerController::class, 'dashboard'])->name('dashboard.buyer');

    Route::get('/buyer/orders', [BuyerController::class, 'orders'])->name('buyer.orders');
    Route::get('/buyer/orders/{id}', [BuyerController::class, 'orderDetails'])->name('buyer.orders.details');
    Route::post('/buyer/orders/{id}/rate', [BuyerController::class, 'rateOrder'])->name('buyer.orders.rate');
});

require __DIR__ . '/auth.php';
