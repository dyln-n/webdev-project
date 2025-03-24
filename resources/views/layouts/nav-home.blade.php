<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
        <!-- Left: Logo -->
        <div class="flex items-center gap-2">
            <x-application-logo class="h-8 w-8" />
            <span class="font-semibold text-xl text-gray-800 dark:text-gray-200">Welcome to Amazon</span>
        </div>

        <!-- Center: Categories -->
        <div class="hidden sm:flex gap-10 font-medium">
            <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Electronics</a>
            <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Fashions</a>
            <a href="#" class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 no-underline">Pet Supplies</a>
        </div>

        <!-- Right: Icons -->
        <div class="flex items-center gap-6">
    <!-- Search -->
    <a href="#" class="text-gray-500 hover:text-indigo-600">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
        </svg>
    </a>

    <!-- User Icon -->
    @auth
        <!-- If logged in: link to dashboard -->
        <a href="{{ Auth::user()->role === 'seller' ? route('dashboard.seller') : route('dashboard.buyer') }}"
           title="My Dashboard" class="text-gray-500 hover:text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </a>
    @else
        <!-- If guest: link to login -->
        <a href="{{ route('register') }}" title="Register" class="text-gray-500 hover:text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </a>
    @endauth

            <!-- Cart Icon -->
            @auth
                @if(Auth::user()->role === 'buyer')
                    <a href="{{ route('cart.index') }}" title="Cart" class="text-gray-500 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h12.9M16 16a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </a>
                @else
                    <a href="#" onclick="alert('Please log in with a buyer account to view cart.')" class="text-gray-500 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h12.9M16 16a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}" title="Login to view cart" class="text-gray-500 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h12.9M16 16a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </a>
            @endauth
        </div>
    </div>
</nav>
