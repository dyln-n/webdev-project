<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
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

    // (6 items per page)
    $products = $products->paginate(6);

    return view('search', compact('products'));
}

public function showCategory($category)
{
    // Fetch products based on the category
    $category = Category::where('name', ucfirst($category))->firstOrFail();

    $products = Product::where('category_id', $category->id)
        ->paginate(6);

    return view('category.index', compact('products', 'category'));
}


}
