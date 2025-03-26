<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user
        $request->authenticate();

        // Regenerate the session to prevent session fixation attacks
        $request->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();
        // transfert guest card if needed
        $this->transferGuestCartIfNeeded($user);        

        if ($user->role === 'seller') {
            return redirect()->route('dashboard.seller');
        } elseif ($user->role === 'buyer') {
            return redirect()->route('dashboard.buyer');
        } else {
            return redirect('/'); // fallback
        }
    }

    /**
     * Transfer guest cart to user account if applicable
     */
    protected function transferGuestCartIfNeeded($user): void
    {
        // Only transfer if:
        // 1. User has no existing cart items
        // 2. Session cart exists
        if (Cart::where('user_id', $user->id)->doesntExist() && 
            session()->has('cart')) {
            try {
                app(\App\Http\Controllers\CartController::class)->saveToDatabase();
            } catch (\Exception $e) {
                Log::error('Cart transfer failed during login: '.$e->getMessage());
                // Continue login process even if cart transfer fails
            }
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
