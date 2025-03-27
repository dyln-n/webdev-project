<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Product Details Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex">
                <!-- Product Image -->
                <div class="w-1/3 flex justify-start">
                    @if ($product->images->first())
                    <img src="{{ asset($product->images->first()->image_path) }}"
                        alt="{{ $product->name }}"
                        class="h-full w-full object-cover rounded-lg">
                    @else
                    <div class="w-full h-full bg-gray-200 text-gray-500 text-xs flex items-center justify-center rounded-lg">
                        N/A
                    </div>
                    @endif
                </div>

                <!-- Product Details (Description, Name, Price, etc.) -->
                <div class="w-2/3 pl-6">
                    <h3 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mt-4">{{ $product->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $product->description }}</p>
                    <!-- Product Rating -->
                    <p class="text-gray-600 dark:text-gray-300 mt-4">
                        @if ($averageRating !== null)
                        Average Rating: {{ number_format($averageRating, 1) }} / 5
                        @else
                        No ratings yet
                        @endif
                    </p>
                    <span class="block text-gray-800 dark:text-gray-100 mt-4 font-bold text-xl">${{ $product->price }}</span>
                    <p class="text-gray-600 dark:text-gray-300 mt-4">Stock: {{ $product->stock }}</p>
                    <p class="text-gray-600 dark:text-gray-300 mt-4">Seller: {{ $product->seller->name }}</p>

                    {{-- <!-- Add to Cart Button (AJAX) -->
                    <div class="mt-6">
                        <button
                            id="add-to-cart-btn"
                            data-product-id="{{ $product->id }}"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}
                    >
                    {{ $product->stock <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                    </button>
                </div>

                <!-- Add to Cart Button -->
                <div class="mt-6">
                    <form action="">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="add-to-cart bg-indigo-600 text-white p-3 rounded-md">Add to Cart</button>
                    </form>
                </div> --}}

                <!-- Add to Cart Button -->
                <div class="mt-6">
                    <button
                        id="add-to-cart-btn"
                        data-product-id="{{ $product->id }}"
                        class="bg-indigo-600 text-white p-3 rounded-md"
                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                    </button>
                </div>

            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript for AJAX Add to Cart -->
    <script>
        document.getElementById('add-to-cart-btn').addEventListener('click', async function() {
            const productId = this.dataset.productId;
            const btn = this;

            btn.disabled = true;
            btn.textContent = 'Adding...';

            try {
                const response = await fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    // Remove Content-Type: application/json
                    // Send as form data instead
                    body: new URLSearchParams({
                        quantity: 1
                    })
                });

                if (!response.ok) throw new Error('Server error');

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    const counter = document.getElementById('cart-count');
                    if (counter) {
                        // Use consistent counter logic
                        counter.textContent = data.cart_count;
                    }
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                alert(error.message || 'Could not add to cart');
                console.error('Add to cart error:', error);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Add to Cart';
            }
        });
    </script>
</x-app-layout>
