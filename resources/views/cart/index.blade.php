<x-app-layout>
    <!-- Checkout Modal -->
    <div id="checkout-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
            <h2 class="text-xl font-semibold mb-6 text-center">Checkout</h2>
            <form id="checkout-form">
                <!-- Shipping Address -->
                <h3 class="text-lg font-bold mb-2">Shipping Address</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <input type="text" name="full_name" placeholder="Full Name" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div>
                        <input type="text" name="phone" placeholder="Phone Number" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div>
                        <input type="text" name="street" placeholder="Street Address" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div>
                        <input type="text" name="apt" placeholder="Apt, Suite, Unit, Building" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div>
                        <input type="text" name="city" placeholder="City" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div>
                        <input type="text" name="province" placeholder="Province / Territory" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div class="md:col-span-2">
                        <input type="text" name="postal_code" placeholder="Postal Code" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                </div>

                <!-- Payment Info -->
                <h3 class="text-lg font-bold mb-2">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="card_number" placeholder="Card Number" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div>
                        <input type="text" name="expiry" placeholder="MM/YY" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div>
                        <input type="text" name="name_on_card" placeholder="Name on Card" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                    <div>
                        <input type="text" name="cvv" placeholder="CVV (Security Code)" class="border rounded px-3 py-2 w-full">
                        <p class="form-error text-red-600 text-sm hidden"></p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" id="cancel-checkout" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Let me think</button>
                    <button type="submit" id="submit-order" class="bg-emerald-800 text-white px-4 py-2 rounded">Place My Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="order-success-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Order Placed Successfully!</h2>
            <p>Thank you for your purchase.</p>
            <a href="{{ route('home') }}" class="mt-4 px-4 py-2 bg-emerald-700 text-white rounded">Go to Homepage</a>
        </div>
    </div>
    
    <!-- Remove Confirmation Modal -->
    <div id="remove-confirm-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Confirm Removal</h2>
            <p>Are you sure you want to remove this item from your cart?</p>
            <div class="mt-4 flex justify-center gap-4">
                <button id="cancel-remove" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">Cancel</button>
                <button id="confirm-remove" class="px-4 py-2 bg-red-600 text-white rounded">Remove</button>
            </div>
        </div>
    </div>

    <!-- Success Notification Modal -->
    <div id="cart-success-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Success!</h2>
            <p id="success-message">Item removed from cart</p>
            <button class="close-modal px-4 py-2 bg-gray-800 text-white rounded">Close</button>
        </div>
    </div>

    <!-- Auth Modal -->
    <div id="auth-required-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-sm text-center max-w-lg w-full">
            <h2 class="text-lg font-semibold mb-4">Account Required</h2>
            <p class="mb-4">You need to register or login to proceed to checkout.</p>
            <div class="mt-4 flex justify-center gap-3">
                <button id="cancel-auth" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
                    Continue Shopping
                </button>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition no-underline">
                    Register
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition no-underline">
                    Login
                </a>
            </div>
        </div>
    </div>

    <!-- Cart display -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">Your Cart</h2>

            @if(count($cart) === 0)
            <p class="text-gray-500">Your cart is empty.</p>
            @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-center"></th>
                            <th class="px-4 py-2 text-center">Product</th>
                            <th class="px-4 py-2 text-center">Price</th>
                            <th class="px-4 py-2 text-center">Quantity</th>
                            <th class="px-4 py-2 text-center">Subtotal</th>
                            <th class="px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                        @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        @endphp
                        <tr class="border-t" id="cart-item-{{ $id }}">
                            <td class="px-4 py-3 text-center">
                                @if(isset($item['image_path']))
                                <img src="{{ asset($item['image_path']) }}"
                                    alt="{{ $item['name'] }}"
                                    class="w-20 h-20 object-cover rounded-md">
                                @else
                                <div class="w-20 h-20 bg-gray-200 text-gray-500 flex items-center justify-center rounded-md">
                                    N/A
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-middle">{{ $item['name'] }}</td>
                            <td class="px-4 py-3 text-center align-middle">${{ number_format($item['price'], 2) }}</td>
                            <td class="px-4 py-3 text-center align-middle">
                                <input type="number" class="cart-quantity w-20 border rounded text-center" data-id="{{ $id }}" value="{{ $item['quantity'] }}" min="1">
                            </td>
                            <td class="px-4 py-3 text-center align-middle item-total">${{ number_format($subtotal, 2) }}</td>
                            <td class="px-4 py-3 text-center align-middle">
                                <button class="remove-item px-4 py-2 bg-red-600 text-white rounded" data-id="{{ $id }}">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-bold border-t">
                            <td colspan="4" class="px-4 py-3 text-right">Total:</td>
                            <td class="px-4 py-3 text-center" id="cart-total">${{ number_format($total, 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

            <div class="mt-6 flex justify-between">
                <a href="{{ route('home') }}" class="bg-cyan-700 text-white px-6 py-2 rounded hover:bg-cyan-800 no-underline">
                    Continue Shopping
                </a>
                @if(!empty($cart))
                <a href="{{ auth()->check() ? route('checkout') : '#' }}" 
                    id="proceed-to-checkout" 
                    class="bg-emerald-800 text-white px-6 py-2 rounded hover:bg-emerald-900 no-underline">
                     Proceed to Checkout
                 </a>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        window.cartRoutes = {
            update: "{{ route('cart.update') }}",
            remove: "{{ route('cart.remove') }}"
        };
        window.checkoutRoutes = {
        store: "{{ route('checkout.store') }}"
    };
    </script>

    @vite(['resources/js/cart/cart.js'])
</x-app-layout>
