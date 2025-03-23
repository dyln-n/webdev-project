<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // calculate total price
    private function calculateTotalPrice($cartItems)
    {
        return $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    // display cart items
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return view('cart.cart', ['message' => 'Your cart is empty.']);
        }
        // Calculate total price
        $totalPrice = $this->calculateTotalPrice($cartItems);
        // Pass total price to the view
        return view('cart.cart', compact('cartItems', 'totalPrice'));
    }

    // add item to cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return back()->with('success', 'Item added to cart');
    }

    // update Cart Item Quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated');
    }

    // remove item from cart
    public function remove($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart');
    }

    // clear cart
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Cart cleared');
    }

    // get cart items for AJAX
    public function getCartItems()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        $totalPrice = $this->calculateTotalPrice($cartItems);

        return response()->json([
            'cartItems' => $cartItems,
            'totalPrice' => number_format($totalPrice, 2)
        ]);
    }
}
