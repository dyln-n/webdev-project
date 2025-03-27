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

    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Your Cart</h2>

        @if(count($cart) === 0)
            <p class="text-gray-500">Your cart is empty.</p>
        @else
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Product</th>
                        <th class="p-2 border">Price</th>
                        <th class="p-2 border">Quantity</th>
                        <th class="p-2 border">Subtotal</th>
                        <th class="p-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                    @php 
                        $subtotal = $item['price'] * $item['quantity']; 
                        $total += $subtotal; 
                    @endphp
                         <tr id="cart-item-{{ $id }}">
                            <td class="p-2 border">
                                <div class="flex items-center">
                                    <!-- Product Image -->
                                    @if(isset($item['image_path']))
                                    <div class="w-16 h-16 mr-3 flex-shrink-0">
                                        <img src="{{ asset('storage/app/public/products/' . $item['image_path']) }}" 
                                             alt="{{ $item['name'] }}"
                                             class="w-full h-full object-cover rounded border border-gray-200">
                                    </div>
                                    @else
                                    <div class="w-16 h-16 mr-3 flex-shrink-0 bg-gray-100 rounded border border-gray-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                    <span>{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="p-2 border">{{ $item['name'] }}</td>
                            <td class="p-2 border">${{ number_format($item['price'], 2) }}</td>
                            <td class="p-2 border">
                                <input type="number" 
                                       class="cart-quantity w-16 border p-1 text-center" 
                                       data-id="{{ $id }}" 
                                       value="{{ $item['quantity'] }}" 
                                       min="1">
                            </td>
                            <td class="p-2 border item-total">${{ number_format($subtotal, 2) }}</td>
                            <td class="p-2 border">
                                <button class="remove-item px-2 py-1 bg-red-800 text-white rounded hover:bg-red-700 transition-colors" 
                                        data-id="{{ $id }}">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-200 font-bold">
                        <td colspan="3" class="p-2 border text-right">Total:</td>
                        <td class="p-2 border" id="cart-total">${{ number_format($total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="bg-cyan-700 text-white px-4 py-2 rounded 
                    no-underline hover:underline hover:bg-cyan-700 transition-colors">
                Continue Shopping
            </a>
            @if(!empty($cart))
                <a href="{{ auth()->check() ? route('checkout') : route('register') }}" class="ms-auto 
                    bg-emerald-800 text-white px-4 py-2 rounded no-underline hover:underline hover:bg-emerald-700 transition-colors">
                    Proceed to Checkout
                </a>
            @endif
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