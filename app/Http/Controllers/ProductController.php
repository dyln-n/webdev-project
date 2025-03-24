<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.details', compact('product'));
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
