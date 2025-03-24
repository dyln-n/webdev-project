<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Logo + Title -->
            <div class="flex items-center space-x-4">
                <a href="{{ Auth::user()?->role == 'seller' ? route('dashboard.seller') : (Auth::user()?->role == 'buyer' ? route('dashboard.buyer') : '/') }}" class="flex items-center space-x-6 no-underline text-gray-800 dark:text-gray-200">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-200 hidden sm:inline">Welcome to Amazon</span>
                </a>
            </div>

            <!-- Category Links -->
            <div class="hidden sm:flex gap-16 font-medium">
                <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Electronics</a>
                <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Fashions</a>
                <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Pet Supplies</a>
            </div>

            <!-- Right Icons -->
            <div class="flex items-center gap-10">
                <a href="{{ route('search') }}" title="Search" class="text-gray-500 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </a>
                <!-- User Icon -->
                @auth
                <a href="{{ Auth::user()->role === 'seller' ? route('dashboard.seller') : route('dashboard.buyer') }}"
                    title="My Dashboard"
                    class="text-gray-500 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
                @else
                <a href="{{ route('register') }}" title="Login/Register" class="text-gray-500 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
                @endauth


                <!-- Cart Icon -->
                @auth
                <a href="#" title="Cart" class="text-gray-500 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h12.9M16 16a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>
                @else
                <a href="{{ route('register') }}" title="Login to view cart" class="text-gray-500 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h12.9M16 16a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>
                @endauth
            </div>
        </div>
    </x-slot>

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