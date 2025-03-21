<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Seller Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-900 dark:text-gray-100">
                    {{ __("Welcome to your Seller Dashboard!") }}
                </p>

                <!-- redirect to manage product page -->
                <div class="mt-4">
                    <a href="{{ route('seller.products') }}">
                        <x-primary-button>
                            {{ __('Manage My Products') }}
                        </x-primary-button>
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
