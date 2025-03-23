@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Your Products</h3>
            <table class="w-full table-fixed text-left" id="product-table">
    <thead>
        <tr>
            <th class="w-12 px-2 py-2"></th>
            <th class="w-1/5 px-4 py-2 text-left">Name</th>
            <th class="w-1/5 px-4 py-2 text-left">Description</th>
            <th class="w-1/5 px-4 py-2 text-left">Price</th>
            <th class="w-1/5 px-4 py-2 text-left">Stock</th>
            <th class="w-1/5 px-4 py-2 text-left">Category</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr class="border-t" data-id="{{ $product->id }}">
                <td class="w-16 px-2 py-2"> <!-- radio 列宽一致 -->
                    <input type="radio" name="selected_product" value="{{ $product->id }}">
                </td>
                <td class="px-4 py-2 product-name">{{ $product->name }}</td>
                <td class="px-4 py-2 product-description truncate" title="{{ $product->description }}">
                    {{ Str::limit($product->description, 15) }}
                </td>
                <td class="px-4 py-2 product-price">{{ $product->price }}</td>
                <td class="px-4 py-2 product-stock">{{ $product->stock }}</td>
                <td class="px-4 py-2 product-category">{{ $product->category->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

            <!-- form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
    <form id="product-form" method="POST" action="{{ route('seller.products.store') }}">
        @csrf
        <input type="hidden" id="product_id" name="product_id" value="">

        <!-- Product Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Product Name')" />
            <x-text-input id="name" class="block w-2/3" type="text" name="name" />
            <div id="name_error" class="text-red-500 text-sm mt-1 hidden">Product name is required.</div>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description" class="block w-2/3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1"></textarea>
            <div id="description_error" class="text-red-500 text-sm mt-1 hidden">Description is required.</div>
        </div>

        <!-- Price and Stock -->
        <div class="flex gap-5 w-2/3">
            <div class="w-[48.5%]">
                <x-input-label for="price" :value="__('Price')" />
                <x-text-input id="price" class="block w-full" type="number" step="0.01" name="price" />
                <div id="price_error" class="text-red-500 text-sm mt-1 hidden">Price must be a non-negative number.</div>
            </div>

            <div class="w-[48.5%]">
                <x-input-label for="stock" :value="__('Stock')" />
                <x-text-input id="stock" class="block w-full" type="number" name="stock" />
                <div id="stock_error" class="text-red-500 text-sm mt-1 hidden">Stock must be a positive integer.</div>
            </div>
        </div>

                    <div class="mb-4 w-[31%] mt-4">
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select id="category_id" name="category_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

<div class="mt-6 flex gap-4">
    <x-primary-button
    class="w-28 h-10 justify-center text-center bg-gray-800 hover:bg-gray-900 text-white"
    id="add-btn">
    {{ __('ADD') }}
</x-primary-button>

    <x-primary-button type="button" class="w-28 h-10 justify-center text-center" id="update-btn" disabled>
        {{ __('UPDATE') }}
    </x-primary-button>

    <x-danger-button type="button" class="w-28 h-10 justify-center text-center" id="delete-btn" disabled>
        {{ __('DELETE') }}
    </x-danger-button>
</div>

            </div>
        </div>
    </div>

    @vite(['resources/js/seller/products.js'])
</x-app-layout>
