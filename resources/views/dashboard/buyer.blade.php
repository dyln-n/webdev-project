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

<!-- Pending Order Modal -->
<div id="pending-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl max-w-xl w-full shadow-lg">
        <h2 class="text-xl font-bold mb-4">Modify or Cancel Order</h2>
        <div id="pending-order-content" class="mb-4">
            <p class="mb-2">This order is still pending. You may:</p>
            <ul class="list-disc list-inside text-gray-700">
                <li>Change product quantities</li>
                <li>Remove unwanted products</li>
                <li>Cancel the entire order</li>
            </ul>
        </div>
        <div class="text-right">
            <button id="close-pending" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Close</button>
            <button id="edit-pending" class="bg-gray-800 text-white px-4 py-2 rounded mr-2">Edit</button>
            <button id="cancel-pending" class="bg-gray-800 text-white px-4 py-2 rounded">Cancel Order</button>
        </div>
    </div>
</div>

<!-- Edit Order Modal -->
<div id="edit-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-2xl shadow-lg">
        <h2 class="text-xl font-bold mb-4">Edit Order</h2>
        <form id="edit-form" method="POST" action="">
            @csrf
            @method('PUT')

            <div id="edit-product-list" class="space-y-2"></div>

            <div class="mt-6 flex justify-between items-center">
                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Update Order</button>
                    <button type="button" id="cancel-order-btn" class="bg-red-600 text-white px-4 py-2 rounded">Cancel Order</button>
                </div>
                <button type="button" class="close-modal bg-gray-300 text-gray-700 px-4 py-2 rounded min-w-[104px]">Close</button>
            </div>
        </form>
    </div>
</div>

<!-- Shipped Info Modal -->
<div id="shipped-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-sm w-full">
        <h2 class="text-lg font-semibold mb-4">Your order has been shipped!</h2>
        <p>It’s on the way and will arrive soon.</p>
        <button class="close-modal mt-4 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Close</button>
    </div>
</div>

<!-- Canceled Info Modal -->
<div id="canceled-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-md text-center max-w-sm w-full">
        <h2 class="text-lg font-semibold mb-4">This order has been canceled.</h2>
        <p>It is no longer editable or reviewable.</p>
        <button class="close-modal mt-4 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Close</button>
    </div>
</div>

<!-- Order List-->
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
                                <button class="toggle-review" data-order-id="{{ $order->id }}" data-status="{{ $order->status }}">
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
