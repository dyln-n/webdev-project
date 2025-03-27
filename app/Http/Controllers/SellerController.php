<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductImage;

class SellerController extends Controller
{
    // Add a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'seller_id' => Auth::id(),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_main' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully!',
            'product' => $product
        ], 200);
    }


    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');

            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_main' => true],
                ['image_path' => $path]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product
        ], 200);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted.'
        ]);
    }
}
