<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-6">
                <form method="GET" action="{{ route('search') }}" class="flex justify-between items-center">
                    <input type="text" name="query" placeholder="Search for products..." class="w-full p-3 border rounded-md" value="{{ request('query') }}">
                    <button type="submit" class="ml-4 bg-indigo-600 text-white p-3 rounded-md">
                        Search
                    </button>
                </form>
            </div>

            <!-- Product List Section -->
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12" id="product-list">
                @forelse($products as $product)
                <div class="product-item bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg" data-product-name="{{ $product->name }}">
                    <!-- Product Image -->
                    <img src="{{ $product->image_url }}" alt="Product Image" class="w-full h-48 object-cover rounded-lg">

                    <!-- Product Name -->
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4">{{ $product->name }}</h3>

                    <!-- Product Description -->
                    <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $product->description }}</p>

                    <!-- Product Price -->
                    <span class="block text-gray-800 dark:text-gray-100 mt-4 font-bold">${{ $product->price }}</span>

                    <!-- View Details Link -->
                    <a href="{{ route('buyer.product.details', $product->id) }}" class="text-indigo-600 hover:text-indigo-800 mt-4 block">View Details</a>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-300">No products found.</p>
                @endforelse
            </div>
        </div>s
    </div>
</x-app-layout>