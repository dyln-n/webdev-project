<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buyer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Your Orders</h3>
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Order ID</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-4 py-2">{{ $order->id }}</td>
                                <td class="px-4 py-2">{{ $order->order_date }}</td>
                                <td class="px-4 py-2">${{ $order->total }}</td>
                                <td class="px-4 py-2">{{ $order->status }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('buyer.orders.show', $order->id) }}" class="text-blue-500">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
