document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('review-modal');
    const thankYouModal = document.getElementById('thank-you-modal');
    const shippedModal = document.getElementById('shipped-modal');
    const canceledModal = document.getElementById('canceled-modal');
    const editModal = document.getElementById('edit-modal');

    const fields = document.getElementById('product-review-fields');
    const reviewForm = document.getElementById('review-form');
    const cancelBtn = document.getElementById('cancel-btn');
    const submitBtn = reviewForm.querySelector('button[type="submit"]');

    function resetAllArrows() {
        document.querySelectorAll('.arrow').forEach(a => a.classList.remove('rotate'));
    }

    document.querySelectorAll('.toggle-review').forEach(button => {
        button.addEventListener('click', async () => {
            const orderId = button.dataset.orderId;
            const status = button.dataset.status;
            const arrow = button.querySelector('.arrow');

            resetAllArrows();
            arrow.classList.add('rotate');

            if (status === 'delivered') {
                fields.innerHTML = '';
                reviewForm.action = `/buyer/orders/${orderId}/rate`;

                const res = await fetch(`/orders/${orderId}/ratings`);
                const ratings = await res.json();

                const reviewed = ratings.filter(item => item.rating || item.review);
                const unreviewed = ratings.filter(item => !item.rating && !item.review);

                reviewed.forEach(item => {
                    const section = document.createElement('div');
                    section.classList.add("flex", "items-start", "mb-4", "gap-4");
                    section.innerHTML = `
                        <img src="${item.image_path}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md mt-1" />
                        <div class="flex-1">
                            <label class="font-semibold block mb-1">${item.name}</label>
                            <div class="mb-2">
                                ${Array.from({ length: 5 }, (_, i) => `
                                    <span class="${i < item.rating ? 'text-yellow-400' : 'text-gray-300'}">&#9733;</span>
                                `).join('')}
                            </div>
                            <p class="text-sm text-gray-600">${item.review || ''}</p>
                        </div>
                    `;
                    fields.appendChild(section);
                });

                if (unreviewed.length > 0) {
                    submitBtn.classList.remove('hidden');
                    cancelBtn.innerText = 'Cancel';

                    unreviewed.forEach(item => {
                        const section = document.createElement('div');
                        section.classList.add("flex", "items-start", "mb-4", "gap-4");
                        section.innerHTML = `
                            <img src="${item.image_path}" alt="${item.name}" class="w-20 h-20 object-cover rounded-md mt-1" />
                            <div class="flex-1">
                                <label class="font-semibold block mb-1">${item.name}</label>
                                <div class="mb-2">
                                    ${Array.from({ length: 5 }, (_, i) => `
                                        <span class="star text-gray-300 cursor-pointer" data-index="${i + 1}" data-product-id="${item.product_id}">&#9733;</span>
                                    `).join('')}
                                </div>
                                <textarea name="reviews[${item.product_id}]" rows="2" class="w-full border rounded p-2 text-sm" placeholder="Write a review (optional)"></textarea>
                            </div>
                        `;
                        fields.appendChild(section);
                    });
                } else {
                    submitBtn.classList.add('hidden');
                    cancelBtn.innerText = 'Close';
                }

                modal.classList.remove('hidden');

            } else if (status === 'pending') {
                const res = await fetch(`/orders/${orderId}/products`);
                const products = await res.json();

                const editList = document.getElementById('edit-product-list');
                editList.innerHTML = '';
                const seenIds = new Set();

                products.forEach(product => {
                    if (seenIds.has(product.id)) return;
                    seenIds.add(product.id);

                    const row = document.createElement('div');
                    row.classList.add('flex', 'items-center', 'justify-between', 'space-x-4', 'mb-4');

                      row.innerHTML = `
                    <div class="flex items-center space-x-4 w-1/2">
                    <img src="${product.image_path}" alt="${product.name}" class="w-20 h-20 object-cover rounded-md border" />
                     <label class="font-semibold">${product.name}</label>
                     </div>
                     <input type="number" min="1" name="items[${product.id}]" value="${product.quantity}" class="w-20 border rounded px-2 py-1 text-right" />
                    <button type="button" class="remove-item bg-red-600 text-white px-4 py-2 rounded" data-product-id="${product.id}">Remove</button>
                    `;

                    editList.appendChild(row);
                });

                editList.querySelectorAll('.remove-item').forEach(btn => {
                    btn.addEventListener('click', () => {
                        btn.closest('div.flex')?.remove();
                    });
                });

                document.getElementById('edit-form').action = `/orders/${orderId}`;
                document.getElementById('cancel-order-btn').dataset.orderId = orderId;

                editModal.classList.remove('hidden');
            } else if (status === 'shipped') {
                shippedModal.classList.remove('hidden');
            } else if (status === 'canceled') {
                canceledModal.classList.remove('hidden');
            }
        });
    });

    // submit ratings
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('star')) {
            const value = parseInt(e.target.dataset.index);
            const productId = e.target.dataset.productId;
            document.querySelector(`input[name="rating[${productId}]"]`)?.setAttribute("value", value);

            const stars = document.querySelectorAll(`.star[data-product-id="${productId}"]`);
            stars.forEach((star, index) => {
                star.classList.toggle('text-yellow-400', index < value);
                star.classList.toggle('text-gray-300', index >= value);
            });
        }

        if (e.target.id === 'close-thank-you') {
            thankYouModal.classList.add('hidden');
        }

        if (e.target.classList.contains('close-modal')) {
            modal.classList.add('hidden');
            shippedModal.classList.add('hidden');
            canceledModal.classList.add('hidden');
            editModal.classList.add('hidden');
            resetAllArrows();
        }
    });

    // submit review
    reviewForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();
        if (result.message) {
            modal.classList.add('hidden');
            thankYouModal.classList.remove('hidden');
        }
    });

    // submit update
    document.getElementById('edit-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const inputs = this.querySelectorAll('input[name^="items["]');
        const items = {};

        inputs.forEach(input => {
            const match = input.name.match(/^items\[(\d+)\]$/);
            if (match) {
                const productId = match[1];
                const quantity = parseInt(input.value);
                if (!isNaN(quantity) && quantity > 0) {
                    items[productId] = quantity;
                }
            }
        });

        const csrfToken = document.querySelector('input[name="_token"]').value;

        const response = await fetch(this.action, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ items })
        });

        if (response.ok) {
            alert('Order updated successfully!');
            window.location.reload();
        } else {
            const error = await response.json();
            alert(error.message || 'Failed to update order.');
        }
    });

    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                modal.classList.add('hidden');
            });
            resetAllArrows();
        });
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            const row = e.target.closest('.flex');
            if (row) row.remove();
        }
    });

    const closeReviewBtn = document.getElementById('cancel-btn');
    if (closeReviewBtn) {
        closeReviewBtn.addEventListener('click', () => {
            document.getElementById('review-modal').classList.add('hidden');
            resetAllArrows();
        });
    }

    document.getElementById('cancel-order-btn').addEventListener('click', async function () {
        const orderId = this.dataset.orderId;

        if (!confirm('Are you sure you want to cancel this order?')) return;

        const response = await fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            alert('Order canceled successfully.');
            location.reload();
        } else {
            alert('Failed to cancel order.');
        }
    });
});
