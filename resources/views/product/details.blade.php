{{-- <x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Product Details Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <!-- Product Image -->
                <img src="{{ $product->image_url }}" alt="Product Image" class="w-full h-96 object-cover rounded-lg">

                <!-- Product Name -->
                <h3 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mt-4">{{ $product->name }}</h3>

                <!-- Product Description -->
                <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $product->description }}</p>

                <!-- Product Price -->
                <span class="block text-gray-800 dark:text-gray-100 mt-4 font-bold text-xl">${{ $product->price }}</span>

                <!-- Product Stock -->
                <p class="text-gray-600 dark:text-gray-300 mt-4">Stock: {{ $product->stock }}</p>

                <!-- Seller Name -->
                <p class="text-gray-600 dark:text-gray-300 mt-4">Seller: {{ $product->seller->name }}</p>

                <!-- Add to Cart Button (Add action later on) -->
                <div class="mt-6">
                    <form action="">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="bg-indigo-600 text-white p-3 rounded-md">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Product Details Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <!-- Product Image -->
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg">

                <!-- Product Info -->
                <div class="mt-4">
                    <h3 class="text-3xl font-semibold text-gray-800 dark:text-gray-100">{{ $product->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $product->description }}</p>
                    
                    <!-- Price & Stock -->
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-800 dark:text-gray-100">${{ number_format($product->price, 2) }}</span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ')' : 'Out of Stock' }}
                        </span>
                    </div>

                    <!-- Seller Info -->
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Sold by: {{ $product->seller->name }}</p>
                </div>

                <!-- Add to Cart Button (AJAX) -->
                <div class="mt-6">
                    <button 
                        id="add-to-cart-btn" 
                        data-product-id="{{ $product->id }}"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-md disabled:opacity-50"
                        {{ $product->stock <= 0 ? 'disabled' : '' }}
                    >
                        {{ $product->stock <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                    </button>
                </div>
            </div>

            <!-- Related Products (Optional) -->
            <div class="mt-12">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">You may also like</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                            <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="w-full h-40 object-cover rounded-lg">
                            <h4 class="font-semibold mt-2">{{ $related->name }}</h4>
                            <p class="text-gray-600 dark:text-gray-300">${{ number_format($related->price, 2) }}</p>
                            <a href="{{ route('buyer.product.details', $related->id) }}" class="inline-block mt-2 text-indigo-600 dark:text-indigo-400 hover:underline">View Details</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX Add to Cart -->
    <script>
        document.getElementById('add-to-cart-btn').addEventListener('click', function() {
            const productId = this.dataset.productId;
            const btn = this;
            
            // Disable button during request
            btn.disabled = true;
            btn.textContent = 'Adding...';

            fetch("{{ route('cart.add', ['id' => ':id']) }}".replace(':id', productId), {
            method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    quantity: 1  // Default quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || 'Product added to cart!');
                // Update UI (e.g., cart counter)
                if (data.cart_count) {
                    const cartCounter = document.getElementById('cart-count');
                    if (cartCounter) cartCounter.textContent = data.cart_count;
                }
            })
            .catch(error => {
                alert('Error: Could not add to cart.');
                console.error(error);
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Add to Cart';
            });
        });
    </script>
</x-app-layout>