<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function show($id)
    {
        $product = Product::with('seller')->findOrFail($id);
        $relatedProducts = Product::where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('product.details', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        // Get products that match the query
        $products = Product::query();

        if ($request->has('query')) {
            $products = $products->where('name', 'like', '%' . $request->input('query') . '%');
        }

        $products = $products->get();

        return view('search', compact('products'));
    }
}
