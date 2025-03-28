<?php
namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\DB;
 use App\Models\Order;
 use App\Models\OrderItem;
 use App\Models\Cart;
 use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'card_number' => 'required|string|max:20',
            'expiry' => 'required|string|max:5',
            'cvv' => 'required|string|max:4',
            'name_on_card' => 'required|string|max:255'
        ]);

        $userId = Auth::id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 400);
        }

        // Validate stock before checkout
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Not enough stock for {$item->product->name}"
                ], 400);
            }
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $userId,
                'order_date' => now(),
                'status' => 'pending',
                'shipping_name' => $validated['full_name'],
                'shipping_phone' => $validated['phone'],
                'shipping_street' => $validated['street'],
                'shipping_city' => $validated['city'],
                'shipping_province' => $validated['province'],
                'shipping_postal_code' => $validated['postal_code'],
                'total' => 0 // Will be calculated below
            ]);

            $total = 0;
            
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Update product stock
                $item->product->decrement('stock', $item->quantity);
                
                $total += $item->quantity * $item->product->price;
            }

            // Update order total
            $order->update(['total' => $total]);
            
            // Clear cart
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
