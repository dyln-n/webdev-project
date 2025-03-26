<x-app-layout>
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
                                <button class="remove-item px-2 py-1 bg-red-500 text-white rounded" 
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
            <a href="{{ route('home') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                Continue Shopping
            </a>
            @if(!empty($cart))
                <a href="{{ auth()->check() ? route('checkout') : route('register') }}"  class="ms-auto bg-green-500 text-white px-4 py-2 rounded">
                    Proceed to Checkout
                </a>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

            // Quantity change handler
            document.querySelectorAll('.cart-quantity').forEach(input => {
                input.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const quantity = Math.max(1, parseInt(this.value)); // Ensure quantity >= 1
                    const row = this.closest('tr');

                    fetch("{{ route('cart.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            id: id,
                            quantity: quantity,
                            is_logged_in: isLoggedIn
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            row.querySelector('.item-total').textContent = '$' + data.subtotal;
                            document.getElementById('cart-total').textContent = '$' + data.total;
                        } else {
                            throw new Error(data.message || 'Update failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message);
                        this.value = this.dataset.oldValue || 1;
                        location.reload();
                    });
                });
            });

            // Item removal handler
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', async function() {
                    if (!confirm('Are you sure you want to remove this item?')) return;
                    
                    const id = this.dataset.id;
                    const row = document.getElementById(`cart-item-${id}`);
                    const button = this;
                    
                    button.disabled = true;
                    button.innerHTML = '<span class="animate-spin">⏳</span>';

                    try {
                        const response = await fetch("{{ route('cart.remove') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ id: id })
                        });

                        const data = await response.json();
                        
                        if (!response.ok) throw new Error(data.message || 'Removal failed');
                        
                        // Animate removal
                        row.style.opacity = '0';
                        await new Promise(resolve => setTimeout(resolve, 300));
                        
                        row.remove();
                        document.getElementById('cart-total').textContent = '$' + data.total;
                        
                        // Check if cart is empty
                        const cartItems = document.querySelectorAll('tbody tr[id^="cart-item-"]');
                        if (cartItems.length === 0) {
                            location.reload();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert(error.message);
                        location.reload(); // Force refresh to sync state
                    } finally {
                        button.disabled = false;
                        button.innerHTML = '❌';
                    }
                });
            });
        });
    </script>
</x-app-layout>