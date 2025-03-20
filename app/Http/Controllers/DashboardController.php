<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function sellerDashboard()
    {
        return view('dashboard.seller');  // Return a view for the seller's dashboard
    }

    public function buyerDashboard()
    {
        return view('dashboard.buyer');  // Return a view for the buyer's dashboard
    }
}
