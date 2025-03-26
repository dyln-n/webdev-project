<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Controllers\CartController; 

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:buyer,seller'],  // Ensure role is either 'buyer' or 'seller'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,  // Save the selected role from the form
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Save guest cart to database after registration
        $this->transferGuestCartToUser($user);

        // Redirect based on the user's role
        if ($user->role == 'seller') {
            return redirect()->route('dashboard.seller');  // Redirect to seller dashboard
        } else {
            return redirect('/'); // Redirect to buyer dashboard
        }
    }

    protected function transferGuestCartToUser($user): void
    {
        try {
            app(CartController::class)->saveToDatabase();
        } catch (\Exception $e) {
            Log::error('Cart transfer failed during registration: '.$e->getMessage());
            // Continue with registration even if cart transfer fails
        }
    }
}