<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckoutController;
use App\Models\Order;
use App\Models\Rating;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

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
        $order = Order::with('orderItems.product.images')->findOrFail($id);

        $products = $order->orderItems->map(function ($item) {
            $image = $item->product->images->first();
            return [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'image_path' => asset($image ? $image->image_path : 'images/placeholder.png'),
                'quantity' => $item->quantity,
            ];
        });

        return response()->json($products);
    });



    Route::get('/orders/{id}/ratings', function ($id) {
        $user = Auth::user();
        $order = Order::with('orderItems.product.images')->where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $results = $order->orderItems->map(function ($item) use ($user) {
            $rating = Rating::where('user_id', $user->id)
                ->where('product_id', $item->product->id)
                ->first();

            $imagePath = $item->product->images->first()->image_path ?? 'images/placeholder.png';

            return [
                'product_id' => $item->product->id,
                'name' => $item->product->name,
                'rating' => $rating?->rating,
                'review' => $rating?->review,
                'image_path' => asset($imagePath),
            ];
        });

        return response()->json($results);
    });



    //TODO: check route
    Route::get('/products', [ProductController::class, 'details'])->name('products.details');
    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');

    Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('auth');

    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/save', [CartController::class, 'saveToDatabase'])->name('cart.save');
    Route::get('/cart/items', [CartController::class, 'getCartItems'])->name('cart.items');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{id}/products', [OrderController::class, 'getProducts']);
    Route::post('/checkout', [OrderController::class, 'checkout'])->middleware('auth');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('buyer.product.details');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/category/{category}', [ProductController::class, 'showCategory'])->name('category.show');


require __DIR__ . '/auth.php';
