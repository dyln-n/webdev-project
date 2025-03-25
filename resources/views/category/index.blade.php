<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Category Title -->
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-200 mb-6">
                {{ $category->name }}
            </h2>

            <!-- Product List Section -->
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12" id="product-list">
                @foreach($products as $product)
                <div class="product-item bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg" data-product-name="{{ $product->name }}">

                    <!-- Product Image -->
                    @if ($product->images->first())
                    <div class="w-full h-48 overflow-hidden flex justify-center items-center">
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="h-full object-cover rounded-lg">
                    </div>
                    @else
                    <div class="w-full h-48 bg-gray-200 text-gray-500 text-xs flex items-center justify-center rounded-lg">
                        N/A
                    </div>
                    @endif

                    <!-- Product Name -->
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-4">{{ $product->name }}</h3>

                    <!-- Product Description -->
                    <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $product->description }}</p>

                    <!-- Product Price -->
                    <span class="block text-gray-800 dark:text-gray-100 mt-4 font-bold">${{ $product->price }}</span>

                    <!-- View Details Link -->
                    <a href="{{ route('buyer.product.details', $product->id) }}" class="text-indigo-600 hover:text-indigo-800 mt-4 block">View Details</a>
                </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
