<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Your Cart</h2>

                <table id="cartTable" class="w-full table-auto border-collapse mb-6">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-left text-sm font-semibold text-gray-700 dark:text-white">
                            <th class="px-4 py-2">Product</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Quantity</th>
                            <th class="px-4 py-2">Subtotal</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 text-sm">
                        <!-- Cart items will be loaded here via AJAX -->
                    </tbody>
                </table>

                <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                    Total: $<span id="totalPrice">0.00</span>
                </h3>
            </div>
        </div>
    </div>

    <script>
        function loadCart() {
            $.ajax({
                url: "{{ route('cart.items') }}",
                type: "GET",
                success: function(response) {
                    let cartHtml = "";
                    response.cartItems.forEach(item => {
                        cartHtml += `
                            <tr id="cart-item-${item.id}" class="border-t">
                                <td class="px-4 py-2">${item.product.name}</td>
                                <td class="px-4 py-2">$${item.product.price.toFixed(2)}</td>
                                <td class="px-4 py-2">${item.quantity}</td>
                                <td class="px-4 py-2">$${(item.product.price * item.quantity).toFixed(2)}</td>
                                <td class="px-4 py-2">
                                    <button onclick="removeItem(${item.id})" class="text-red-500 hover:underline">
                                        Remove
                                    </button>
                                </td>
                            </tr>`;
                    });

                    $("#cartTable tbody").html(cartHtml);
                    $("#totalPrice").text(response.totalPrice);
                }
            });
        }

        function removeItem(itemId) {
            $.ajax({
                url: `/cart/remove/${itemId}`,
                type: "POST",
                data: {_token: "{{ csrf_token() }}" },
                success: function(response) {
                    if (response.success) {
                        $(`#cart-item-${itemId}`).remove();
                        loadCart();
                    }
                }
            });
        }

        $(document).ready(function() {
            loadCart();
        });
    </script>
</x-app-layout>
