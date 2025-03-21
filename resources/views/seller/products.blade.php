<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage My Products') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- add a new product -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('seller.products.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Product Name')" />
                        <x-text-input id="name" class="block w-3/4" type="text" name="name" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="block w-3/4 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1"></textarea>
                    </div>

                    <div class="flex gap-5 w-3/4">
                        <div class="w-[48.5%]">
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" class="block w-full" type="number" step="0.01" name="price" required />
                        </div>

                        <div class="w-[48.5%]">
                            <x-input-label for="stock" :value="__('Stock')" />
                            <x-text-input id="stock" class="block w-full" type="number" name="stock" required />
                        </div>
                    </div>

                    <div class="mb-4 w-[36.5%] mt-4">
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select id="category_id" name="category_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-primary-button>
                        {{ __('Add Product') }}
                    </x-primary-button>
                </form>
            </div>

            <!-- product list -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Your Products</h3>
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Price</th>
                            <th class="px-4 py-2 text-left">Stock</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($products as $product)
                            <tr class="border-t" data-id="{{ $product->id }}">
                                <td class="px-4 py-2">
                                    <span class="editable-text cursor-pointer">{{ $product->name }}</span>
                                    <input type="text" name="name" value="{{ $product->name }}" class="edit-input hidden w-32 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1" />
                                </td>
                                <td class="px-4 py-2">
                                    <span class="editable-text cursor-pointer">${{ $product->price }}</span>
                                    <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="edit-input hidden w-24 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1" />
                                </td>
                                <td class="px-4 py-2">
                                    <span class="editable-text cursor-pointer">{{ $product->stock }}</span>
                                    <input type="number" name="stock" value="{{ $product->stock }}" class="edit-input hidden w-20 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1" />
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex gap-2">
                                        <x-primary-button onclick="enableAllEdits(this)">
                                            {{ __('Update') }}
                                        </x-primary-button>
                                        <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>
                                                {{ __('Delete') }}
                                            </x-danger-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@vite(['resources/js/seller/products.js'])
</x-app-layout>
