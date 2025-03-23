<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function sellerDashboard()
    {
        $products = Product::where('seller_id', Auth::id())->get();
        $categories = Category::all();

        return view('dashboard.seller', compact('products', 'categories'));
    }

    public function buyerDashboard()
    {
        return view('dashboard.buyer');  // Return a view for the buyer's dashboard
    }
}
