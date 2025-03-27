<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Category Title -->
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-200 mb-6">
                {{ $category->name }}
            </h2>

            <!-- Category Description -->
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                {{ $category->description }}
            </p>

            <!-- Sorting Dropdown -->
            <div class="mb-6">
                <form method="GET" class="flex justify-start items-center">
                    <label for="sort" class="mr-4 text-gray-800 dark:text-gray-100">Sort by:</label>
                    <select name="sort" id="sort" class="p-2 border rounded-md w-40" onchange="this.form.submit()">
                        <!-- Default empty option for 'no sorting' -->
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Low to High</option>
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>High to Low</option>
                    </select>
                </form>
            </div>

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
                {{ $products->appends(['sort' => request()->get('sort')])->links() }}
            </div>

        </div>
    </div>
</x-app-layout>