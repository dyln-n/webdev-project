<style>
    .product-image-cell img:hover {
        z-index: 50;
        position: relative;
    }
</style>

<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Your Products</h2>
                <button id="add-btn" class="bg-gray-900 text-white px-4 py-2 rounded">Add Product</button>
            </div>

            <!-- Product Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full table-fixed text-left" id="product-table">
                    <thead>
                        <tr>
                            <th class="w-1/6 px-4 py-2 text-center">Image</th>
                            <th class="w-1/6 px-4 py-2 text-center">Name</th>
                            <th class="w-1/6 px-4 py-2 text-left">Description</th>
                            <th class="w-1/6 px-4 py-2 text-center">Price</th>
                            <th class="w-1/6 px-4 py-2 text-center">Stock</th>
                            <th class="w-1/6 px-4 py-2 text-center">Category</th>
                            <th class="w-1/6 px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr
                            class="border-t"
                            data-id="{{ $product->id }}"
                            data-category-id="{{ $product->category_id }}"
                            data-image-path="{{ $product->images->first()?->image_path }}">
                            <!-- Image -->
                            <td class="px-2 py-2 text-center align-middle">
                                @if ($product->images->first())
                                <img src="{{ asset($product->images->first()->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-18 h-18 object-cover rounded-md transition-transform duration-200 hover:scale-105 relative z-10">
                                @else
                                <div class="w-18 h-18 bg-gray-200 text-gray-500 text-xs flex items-center justify-center rounded-md">
                                    N/A
                                </div>
                                @endif
                            </td>

                            <!-- Name -->
                            <td class="px-4 py-2 text-center product-name whitespace-normal align-middle"
                                data-full-name="{{ $product->name }}">
                                {{ $product->name }}
                            </td>

                            <!-- Description -->
                            <td class="px-4 py-2 text-left product-description whitespace-normal align-middle"
                                title="{{ $product->description }}"
                                data-full-description="{{ $product->description }}">
                                {{ Str::limit($product->description, 60) }}
                            </td>

                            <!-- Price -->
                            <td class="px-4 py-2 text-center product-price align-middle">{{ $product->price }}</td>

                            <!-- Stock -->
                            <td class="px-4 py-2 text-center product-stock align-middle">{{ $product->stock }}</td>

                            <!-- Category -->
                            <td class="px-4 py-2 text-center product-category align-middle">{{ $product->category->name }}</td>

                            <!-- Actions -->
                            <td class="px-4 py-2 text-center align-middle">
                                <button class="action-btn text-gray-600 hover:text-black" data-id="{{ $product->id }}">
                                    <svg class="w-5 h-5 inline-block transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Add/Update Modal -->
            <div id="product-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-40 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-2xl relative">
                    <h2 id="modal-title" class="text-xl font-semibold mb-4">Manage Product</h2>
                    <form id="product-form" method="POST" action="{{ route('seller.products.store') }}">
                        @csrf
                        <input type="hidden" id="product_id" name="product_id" value="" enctype="multipart/form-data">

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" class="block w-full" type="text" name="name" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description"
                                class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1"></textarea>
                        </div>

                        <div class="flex gap-5">
                            <div class="w-1/2">
                                <x-input-label for="price" :value="__('Price')" />
                                <x-text-input id="price" class="block w-full" type="number" step="0.01" name="price" />
                            </div>
                            <div class="w-1/2">
                                <x-input-label for="stock" :value="__('Stock')" />
                                <x-text-input id="stock" class="block w-full" type="number" name="stock" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id"
                                class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="product-image" class="block font-medium text-sm text-gray-700">Product Image</label>
                            <input type="file" name="image" id="product-image" accept="image/*" class="mt-1 block w-full border rounded px-3 py-2">
                            <div id="image-preview-wrapper" class="mb-4 hidden">
                                <label class="block font-medium text-sm text-gray-700">Current Image</label>
                                <img id="product-preview-img" class="w-24 h-24 object-cover rounded-md border" src="" alt="Preview" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <!-- 左侧两个按钮 -->
                            <div class="flex gap-4">
                                <button
                                    type="submit"
                                    id="modal-submit-btn"
                                    class="bg-gray-800 text-white px-4 py-2 rounded w-40 h-10 whitespace-nowrap text-center">
                                    Update Product
                                </button>
                                <button
                                    type="button"
                                    id="modal-delete-btn"
                                    class="bg-red-600 text-white px-4 py-2 rounded w-40 h-10 whitespace-nowrap text-center">
                                    Delete Product
                                </button>
                            </div>

                            <!-- 右侧关闭按钮 -->
                            <button
                                type="button"
                                class="close-modal bg-gray-300 text-gray-800 px-4 py-2 rounded w-28 h-10 whitespace-nowrap text-center">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div id="delete-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-40 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
                    <h2 class="text-lg font-bold mb-4 text-center">Confirm Deletion</h2>
                    <p class="mb-6 text-center text-gray-700">Are you sure you want to delete this product?</p>
                    <div class="flex justify-center gap-4">
                        <button type="button" id="confirm-delete-btn"
                            class="bg-red-600 text-white px-4 py-2 rounded w-28 h-10">Delete</button>
                        <button type="button" id="cancel-delete-btn"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded w-28 h-10">Cancel</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @vite(['resources/js/seller/products.js'])
</x-app-layout>
