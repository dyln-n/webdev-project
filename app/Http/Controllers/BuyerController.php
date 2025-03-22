<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('user_id', Auth::id())->get();
        return view('dashboard.buyer', compact('orders'));
    }

    public function orderDetails($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('dashboard.order_details', compact('order'));
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        auth()->user()->ratings()->create([
            'product_id' => $id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Review submitted!');
    }
}
