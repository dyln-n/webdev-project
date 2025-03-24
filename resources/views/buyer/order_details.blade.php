<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Order #{{ $order->id }}</h3>

                <p class="mb-2 text-gray-600">Order Date: {{ $order->created_at->format('F j, Y') }}</p>
                <p class="mb-2 text-gray-600">Total Price: ${{ number_format($order->total, 2) }}</p>
                <p class="mb-4 text-gray-600">Status: <span class="font-semibold">{{ ucfirst($order->status) }}</span></p>

                <h4 class="text-lg font-semibold mb-2">Items</h4>
                <ul class="list-disc list-inside">
                    @foreach($order->items as $item)
                    <li>
                        {{ $item->product->name }} (x{{ $item->quantity }}) -
                        ${{ number_format($item->price, 2) }}
                    </li>
                    @endforeach
                </ul>

                <h4 class="text-lg font-semibold mt-4 mb-2">Leave a Review</h4>
                <form action="{{ route('buyer.orders.rate', $order->id) }}" method="POST">
                    @csrf
                    <label for="rating" class="block font-medium">Rating</label>
                    <select name="rating" id="rating" class="w-24 border-gray-300 rounded-lg">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }} Stars</option>
                            @endfor
                    </select>

                    <label for="review" class="block font-medium mt-2">Review</label>
                    <textarea name="review" id="review" class="w-full border-gray-300 rounded-lg"></textarea>

                    <x-primary-button class="mt-4">
                        Submit Review
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
    @vite(['resources/js/buyer/buyer.js'])
</x-app-layout>