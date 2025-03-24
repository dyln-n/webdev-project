<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Your Orders</h3>

                @if($orders->isEmpty())
                <p class="text-gray-500">You have no orders yet.</p>
                @else
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Order ID</th>
                            <th class="px-4 py-2">Total Price</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">${{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-2">{{ ucfirst($order->status) }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('buyer.orders.details', $order->id) }}" class="text-blue-500 hover:underline">View Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
    @vite(['resources/js/buyer/buyer.js'])
</x-app-layout>