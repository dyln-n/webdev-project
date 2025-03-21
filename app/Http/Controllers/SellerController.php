<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    // seller dashboard
    public function dashboard()
    {
        return view('dashboard.seller');
    }

    // seller products list
    public function index()
    {
        $products = Product::where('seller_id', Auth::id())->get();
        $categories = Category::all();
        return view('seller.products', compact('products', 'categories'));
    }

    // add a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'seller_id' => Auth::id(),
        ]);

        return redirect()->route('seller.products')->with('success', 'Product added successfully!');
    }

    // update a product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    // delete a product
    public function destroy($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->delete();

        return redirect()->route('seller.products')->with('success', 'Product deleted successfully!');
    }
}
