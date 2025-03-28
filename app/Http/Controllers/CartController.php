<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // create index method
    public function index()
{
    if (Auth::check()) {
        // Get cart from db
        $cartItems = Cart::where("user_id", Auth::id())->with("product.images")->get();

        $formattedCart = [];
        foreach ($cartItems as $item) {
            $product = $item->product;
            $image = $product->images->first();
            $imagePath = $image ? $image->image_path : 'images/placeholder.png';

            $formattedCart[$product->id] = [
                'id' => $item->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item->quantity,
                'image_path' => $imagePath, 
                'product' => $product
            ];
        }

        return view('cart.index', ['cart' => $formattedCart]);
    } else {
        // Guest user (session-based cart)
        $cart = Session::get('cart', []);

        foreach ($cart as $id => &$item) {
            $product = Product::with('images')->find($id);

            $item['product'] = $product;

            if ($product && $product->images->first()) {
                $item['image_path'] = $product->images->first()->image_path;
            } else {
                $item['image_path'] = 'images/placeholder.png';
            }
        }

        return view('cart.index', ['cart' => $cart]);
    }
}

    // addToCart method
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);   
        
        // Check product availability
        if ($product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Product out of stock'
            ], 400);
        }

        if (Auth::check()) {
            // AUTH USER - DATABASE CART
            DB::transaction(function () use ($product, $id) {
                $cartItem = Cart::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'product_id' => $id
                    ],
                    [
                        'quantity' => DB::raw('quantity + 1')
                    ]
                ); 
                // Decrement product stock
                $product->decrement('stock');
            });

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => Auth::user()->cart()->count(), // For auth counter
            ]);
        } else {
            // Get cart from session or create a new one
            $cart = Session::get('cart', []);

            // if product is already in cart
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += 1;
            } else {
                // Add product to cart
                $cart[$id] = [
                    'id' => $id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                    'product' => $product->toArray()
                ];
            }

            // save cart back to session
            Session::put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => array_sum(array_column($cart, 'quantity')), // For guest counter
                'cart' => $cart
            ]);
        }
    }

    /* ************************************  */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'is_logged_in' => 'sometimes|boolean'
        ]);
        
        if (Auth::check()) {
            return $this->updateDatabaseCart($request);
        }
        return $this->updateSessionCart($request);
    }

    // for guest (session)
    public function updateSessionCart(Request $request)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            
            $subtotal = $cart[$request->id]['price'] * $cart[$request->id]['quantity'];

            Session::put('cart', $cart);

            return response()->json([
                'success' => true,
                'subtotal' => number_format($subtotal, 2),
                'total' => $this->calculateTotal($cart)
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Item not found in cart']);
    }

    // Calculate Total Helper
    private function calculateTotal(array $cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return number_format($total, 2);
    }

    // For Users (Database)
    public function updateDatabaseCart(Request $request) 
    {
        $cartItem = Cart::where([
            'user_id' => Auth::id(),
            'product_id' => $request->id
        ])->first();
        
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        $product = Product::find($cartItem->product_id);
        $requestedQuantity = (int)$request->quantity;
        $currentQuantity = $cartItem->quantity;
        $quantityChange = $requestedQuantity - $currentQuantity;
        
        // Check stock availability
        if ($quantityChange > 0 && $product->stock < $quantityChange) {
            $maxAvailable = $currentQuantity + $product->stock;
            return response()->json([
                'success' => false,
                'message' => "Out of stock. Only {$maxAvailable} items available.",
                'max_allowed' => $maxAvailable
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Update cart quantity
            $cartItem->update(['quantity' => $requestedQuantity]);
            
            // Adjust product stock
            if ($quantityChange != 0) {
                $product->decrement('stock', $quantityChange);
            }
    
            $subtotal = $product->price * $requestedQuantity;
            $total = $this->calculateDatabaseCartTotal();
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'subtotal' => number_format($subtotal, 2),
                'total' => number_format($total, 2),
                'stock_left' => $product->fresh()->stock
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateDatabaseCartTotal()
    {
    return Cart::where('user_id', Auth::id())
        ->with('product')
        ->get()
        ->sum(fn($item) => $item->product->price * $item->quantity);
    }
    // Migration Helper
    public function saveToDatabase()
    {
        if (!Auth::check()) {
            return false; // Early exit for guests
        }

        $sessionCart = Session::get('cart', []);
        
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $item) {
                $product = Product::find($productId);
    
                // Check if there is enough stock before adding to cart
                if ($item['quantity'] > $product->stock) {
                    return response()->json(['error' => 'Insufficient stock for product: ' . $product->name], 400);
                }
                
                // Add to database cart
                Cart::updateOrCreate(
                    ['user_id' => Auth::id(), 'product_id' => $productId],
                    ['quantity' => DB::raw('quantity + ' . $item['quantity'])]
                );

                // Decrease stock
                $product->decrement('stock', $item['quantity']);
            }

            Session::forget('cart');
        }
        return true;
    }


    /* ************************************  */
    // remove item
    public function removeFromCart(Request $request)
    {
        if (Auth::check()) {
            // For logged-in users - get price before deleting
            $cartItem = Cart::with('product')
                ->where('user_id', Auth::id())
                ->where('product_id', $request->id)
                ->first();
            
            $total = 0;
            
            if ($cartItem) {
                // Calculate current total before removal
                $total = Cart::where('user_id', Auth::id())
                    ->with('product')
                    ->get()
                    ->sum(function($item) {
                        return $item->product->price * $item->quantity;
                    });
                
                // Now delete the item
                $cartItem->delete();
                
                // Subtract removed item's value from total
                $total -= ($cartItem->product->price * $cartItem->quantity);
            }
        } else {
            // For guests - session cart
            $cart = Session::get('cart', []);
            
            if (isset($cart[$request->id])) {
                $removedItemPrice = $cart[$request->id]['price'] * $cart[$request->id]['quantity'];
                unset($cart[$request->id]);
                Session::put('cart', $cart);
                
                $total = $this->calculateTotal($cart);
            }
        }

        return response()->json([
            'success' => true,
            'total' => number_format(max(0, $total), 2) // Ensure total isn't negative
        ]);
    }

}