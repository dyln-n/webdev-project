<x-app-layout>
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
</x-app-layout>
