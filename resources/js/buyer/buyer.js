document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".view-order-btn").forEach(button => {
        button.addEventListener("click", function () {
            let orderId = this.dataset.orderId;
            window.location.href = `/buyer/orders/${orderId}`;
        });
    });

    document.querySelectorAll(".cancel-order-btn").forEach(button => {
        button.addEventListener("click", function () {
            let orderId = this.dataset.orderId;
            
            if (confirm("Are you sure you want to cancel this order?")) {
                fetch(`/buyer/orders/${orderId}/cancel`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Order canceled successfully.");
                        location.reload();
                    } else {
                        alert("Failed to cancel order.");
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });

    document.querySelectorAll(".submit-review-btn").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.dataset.productId;
            let rating = document.querySelector(`#rating-${productId}`).value;
            let review = document.querySelector(`#review-${productId}`).value;

            fetch(`/buyer/orders/${productId}/rate`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ rating, review })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Review submitted successfully.");
                    location.reload();
                } else {
                    alert("Failed to submit review.");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});
