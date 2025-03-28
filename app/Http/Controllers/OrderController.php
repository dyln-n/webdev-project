<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function update(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $items = $request->input('items', []);

        if (empty($items)) {
            return response()->json(['message' => 'No valid items submitted.'], 422);
        }

        DB::beginTransaction();

        try {
            OrderItem::where('order_id', $order->id)->delete();

            $total = 0;

            foreach ($items as $productId => $qty) {
                if ($qty > 0) {
                    $product = Product::find($productId);
                    if (!$product) continue;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'price' => $product->price,
                    ]);

                    $total += $product->price * $qty;
                }
            }

            $order->total = $total;
            $order->save();

            DB::commit();
            return response()->json(['message' => 'Order updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Update failed.'], 500);
        }
    }

    public function getProducts($orderId)
    {
        $order = Order::with('orderItems.product.images')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $products = $order->orderItems->map(function ($item) {
            return [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'image_path' => $item->product->main_image_path
            ];
        });

        return response()->json($products);
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found or not cancelable.'], 404);
        }

        $order->status = 'canceled';
        $order->save();

        return response()->json(['message' => 'Order canceled successfully.']);
    }

    public function checkout(Request $request)
    {
        $userId = Auth::id();
        $cartItems = DB::table('cart')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->quantity * DB::table('products')->where('id', $item->product_id)->value('price');
        }

        DB::beginTransaction();
        try {
            $orderId = DB::table('orders')->insertGetId([
                'user_id'    => $userId,
                'order_date' => now(),
                'total'      => $total,
                'status'     => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($cartItems as $item) {
                $price = DB::table('products')->where('id', $item->product_id)->value('price');

                DB::table('order_items')->insert([
                    'order_id'   => $orderId,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $price,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::table('cart')->where('user_id', $userId)->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order. Please try again.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
