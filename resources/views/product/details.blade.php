<x-app-layout>
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
                        <form action="">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="bg-indigo-600 text-white p-3 rounded-md">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>