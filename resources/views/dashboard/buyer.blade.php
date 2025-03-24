@php
$pageTitle = 'Buyer Dashboard';
@endphp

<style>
    .star.text-yellow-400 {
        color: #facc15 !important;
    }

    .arrow {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .arrow.rotate {
        transform: rotate(90deg);
    }
</style>

<!-- Review Modal -->
<div id="review-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-xl shadow-lg">
        <h2 class="text-xl font-bold mb-4">Rate and Review</h2>
        <form id="review-form" method="POST" action="">
            @csrf
            <div id="product-review-fields"></div>

            <div class="mt-4">
                <label for="username" class="block font-semibold mb-1">Username / Nickname</label>
                <input type="text" id="username" name="username" placeholder="Enter your name" class="w-full border rounded p-2" />
            </div>

            <div class="mt-4 text-right">
                <button type="button" id="cancel-btn" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- Thank You Modal -->
<div id="thank-you-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-sm w-full">
        <h2 class="text-lg font-semibold mb-4">Thank you!</h2>
        <p>Your feedback has been submitted.</p>
        <button id="close-thank-you" class="mt-4 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Close</button>
    </div>
</div>

<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Your Orders</h3>
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Product</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="px-4 py-2 max-w-[200px] truncate" title="{{ $order->orderItems->pluck('product.name')->join(', ') }}">
                                {{ \Illuminate\Support\Str::limit($order->orderItems->pluck('product.name')->join(', '), 20) }}
                            </td>
                            <td class="px-4 py-2">${{ $order->total }}</td>
                            <td class="px-4 py-2">{{ $order->status }}</td>
                            <td class="px-4 py-2">{{ $order->order_date }}</td>
                            <td class="px-4 py-2">
                                <button class="toggle-review" data-order-id="{{ $order->id }}">
                                    <svg class="arrow w-5 h-5 transition-transform transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @vite(['resources/js/buyer/buyer.js'])
</x-app-layout>