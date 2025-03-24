<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Models\Order;
use App\Models\Rating;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard/seller', [DashboardController::class, 'sellerDashboard'])->name('dashboard.seller');
    Route::post('/seller/products', [SellerController::class, 'store'])->name('seller.products.store');
    Route::put('/seller/products/{id}', [SellerController::class, 'update'])->name('seller.products.update');
    Route::delete('/seller/products/{id}', [SellerController::class, 'destroy'])->name('seller.products.destroy');

    Route::get('/dashboard/buyer', [BuyerController::class, 'dashboard'])->name('dashboard.buyer');
    Route::get('/buyer/orders', [BuyerController::class, 'orders'])->name('buyer.orders');
    Route::get('/buyer/orders/{id}', [BuyerController::class, 'orderDetails'])->name('buyer.orders.details');
    Route::post('/buyer/orders/{id}/rate', [BuyerController::class, 'rateOrder'])->name('buyer.orders.rate');

    Route::get('/orders/{id}/products', function ($id) {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return $order->orderItems->map(fn($item) => [
            'id' => $item->product->id,
            'name' => $item->product->name
        ]);
    });

    // routes/web.php
    Route::get('/orders/{id}/ratings', function ($id) {
        $user = Auth::user();
        $order = Order::with('orderItems.product')->findOrFail($id);

        return $order->orderItems->map(function ($item) use ($user) {
            $rating = Rating::where('user_id', $user->id)
                ->where('product_id', $item->product->id)
                ->first();

            return [
                'name' => $item->product->name,
                'rating' => $rating?->rating,
                'review' => $rating?->review,
            ];
        });
    });

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/items', [CartController::class, 'getCartItems'])->name('cart.items');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

require __DIR__ . '/auth.php';
