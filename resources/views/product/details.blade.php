<x-app-layout>
    <!-- Add to Cart Success Modal -->
    <div id="add-to-cart-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Success!</h2>
            <p id="add-to-cart-message">Item added to your cart</p>
            <div class="mt-4 flex justify-center gap-4">
                <button id="continue-shopping" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">Continue Shopping</button>
                <button id="view-cart" class="px-4 py-2 bg-gray-800 text-white rounded">View Cart</button>
            </div>
        </div>
    </div>

    <!-- Error Modal (Reuse this for other errors) -->
    <div id="error-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Oops!</h2>
            <p id="error-message"></p>
            <button class="close-modal mt-4 px-4 py-2 bg-gray-800 text-white rounded">Close</button>
        </div>
    </div>

    <!-- item details -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Product Details Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex">
                <!-- Product Image -->
                <div class="w-1/3 flex justify-start">
                    @if ($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
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

                    <!-- Add to Cart Button -->
                    <div class="mt-6">
                        <button 
                            id="add-to-cart-btn" 
                            data-product-id="{{ $product->id }}"
                            class="bg-indigo-600 text-white p-3 rounded-md"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}
                        >
                            {{ $product->stock <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

     <!-- JavaScript for AJAX Add to Cart -->
     <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const successModal = document.getElementById('add-to-cart-modal');
            const errorModal = document.getElementById('error-modal');
            
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', async function() {
                    const productId = this.dataset.productId;
                    const btn = this;
                    
                    btn.disabled = true;
                    btn.innerHTML = `
                        <span class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Adding...
                        </span>
                    `;

                    try {
                        const response = await fetch(`/cart/add/${productId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: new URLSearchParams({
                                quantity: 1
                            })
                        });

                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Server returned unexpected response');
                        }

                        const data = await response.json();
                        
                        if (!response.ok) {
                            throw new Error(data.message || 'Could not add to cart');
                        }

                        // Show success modal
                        document.getElementById('add-to-cart-message').textContent = data.message;
                        successModal.classList.remove('hidden');
                        
                        // Update cart counter if exists
                        const counter = document.getElementById('cart-count');
                        if (counter) {
                            counter.textContent = data.cart_count;
                        }
                    } catch (error) {
                        console.error('Add to cart error:', error);
                        document.getElementById('error-message').textContent = error.message;
                        errorModal.classList.remove('hidden');
                    } finally {
                        btn.disabled = false;
                        btn.textContent = 'Add to Cart';
                    }
                });
            }

            // Modal event handlers
            document.getElementById('continue-shopping')?.addEventListener('click', () => {
                document.getElementById('add-to-cart-modal').classList.add('hidden');
            });

            document.getElementById('view-cart')?.addEventListener('click', () => {
                window.location.href = "{{ route('cart.index') }}";
            });

            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                        modal.classList.add('hidden');
                    });
                });
            });
        });
    </script>
</x-app-layout>