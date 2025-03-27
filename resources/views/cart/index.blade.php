<x-app-layout>
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
                <a href="{{ auth()->check() ? route('checkout') : route('register') }}" class="bg-emerald-800 text-white px-6 py-2 rounded hover:bg-emerald-900 no-underline">
                    Proceed to Checkout
                </a>
                @endif
            </div>
        </div>
    </div>

    @vite(['resources/js/cart/cart.js'])

    <script>
        window.cartRoutes = {
            update: "{{ route('cart.update') }}",
            remove: "{{ route('cart.remove') }}"
        };
    </script>
</x-app-layout>
