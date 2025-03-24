<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
        <!-- Left: Logo + Dashboard Title -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('home') }}">
                <x-application-logo class="h-8 w-8" />
            </a>
            @auth
                <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    {{ $pageTitle ?? (Auth::user()->role === 'seller' ? 'Seller Dashboard' : 'Buyer Dashboard') }}
                </span>
            @endauth
        </div>

        <!-- Right: Dropdown -->
        <div class="flex items-center space-x-4">
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm text-gray-600 dark:text-gray-300 font-medium hover:text-gray-800 dark:hover:text-white">
                            {{ Auth::user()->name }}
                            <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @endauth
        </div>
    </div>
</nav>
