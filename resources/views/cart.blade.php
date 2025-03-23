@extends('layouts.app')

@section('content')
    <h2>Your Cart</h2>
    <table id="cartTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Cart items will be loaded here via AJAX -->
        </tbody>
    </table>

    <h3>Total: $<span id="totalPrice">0.00</span></h3>

    <script>
        function loadCart() {
            $.ajax({
                url: "{{ route('cart.items') }}",
                type: "GET",
                success: function(response) {
                    let cartHtml = "";
                    response.cartItems.forEach(item => {
                        cartHtml += `
                            <tr id="cart-item-${item.id}">
                                <td>${item.product.name}</td>
                                <td>$${item.product.price.toFixed(2)}</td>
                                <td>${item.quantity}</td>
                                <td>$${(item.product.price * item.quantity).toFixed(2)}</td>
                                <td>
                                    <button onclick="removeItem(${item.id})">Remove</button>
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
@endsection
