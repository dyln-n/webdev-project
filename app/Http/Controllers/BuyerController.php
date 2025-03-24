<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->get();
        return view('dashboard.buyer', compact('orders'));
    }

    public function orderDetails($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('dashboard.order_details', compact('order'));
    }

    // BuyerController.php
    public function rateOrder(Request $request, $id)
    {
        $user = auth()->user();

        foreach ($request->input('rating', []) as $productId => $rating) {
            $review = $request->input("review.$productId", null);

            if ($rating || $review) {
                $user->ratings()->updateOrCreate(
                    ['product_id' => $productId],
                    ['rating' => $rating, 'review' => $review]
                );
            }
        }

        return response()->json(['message' => 'Thank you for your feedback!']);
    }
}
