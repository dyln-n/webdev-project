<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $sessionCart = Session::get('cart', []);

                foreach ($sessionCart as $id => $item) {
                    Cart::updateOrCreate(
                        ['user_id' => $user->id, 'product_id' => $id],
                        ['quantity' => $item['quantity']]
                    );
                }

                Session::forget('cart'); // Clear session cart after merging
            }
        });
    }
}
