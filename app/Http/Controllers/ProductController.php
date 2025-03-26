<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
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

        $products = Product::query();
        if ($request->has('query')) {
            $products = $products->where('name', 'like', '%' . $request->input('query') . '%');
        }

        // Get the sort parameter
        $sortOrder = $request->get('sort', 'newest'); // Default sorting by newest

        if ($sortOrder === 'newest') {
            // Sort by the creation date 
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($sortOrder === 'asc' || $sortOrder === 'desc') {
            // Sorting by price 
            $products = $products->orderBy('price', $sortOrder);
        }

        $products = $products->paginate(6);

        return view('search', compact('products'));
    }



    public function showCategory($categoryName, Request $request)
    {
        $category = Category::where('name', $categoryName)->firstOrFail();
        $sortOrder = $request->get('sort', 'newest');

        // Order by creation date
        if ($sortOrder === 'newest') {
            $products = Product::where('category_id', $category->id)
                ->orderBy('created_at', 'desc')
                ->paginate(6);
        } elseif ($sortOrder === 'asc' || $sortOrder === 'desc') {
            // Sorting by price 
            $products = Product::where('category_id', $category->id)
                ->orderBy('price', $sortOrder)
                ->paginate(6);
        }

        return view('category.show', compact('category', 'products'));
    }
}
