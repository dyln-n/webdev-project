document.addEventListener('DOMContentLoaded', () => {
    let lastOpenedOrderId = null;

    const modal = document.getElementById('review-modal');
    const thankYouModal = document.getElementById('thank-you-modal');
    const fields = document.getElementById('product-review-fields');
    const reviewForm = document.getElementById('review-form');
    const cancelBtn = document.getElementById('cancel-btn');
    const submitBtn = reviewForm.querySelector('button[type="submit"]');
    const usernameGroup = document.getElementById('username-group');

    function toggleArrow(arrowEl, down = true) {
        if (down) {
            arrowEl.classList.add('rotate');
        } else {
            arrowEl.classList.remove('rotate');
        }
    }

    function resetAllArrows() {
        document.querySelectorAll('.arrow').forEach(arrow => arrow.classList.remove('rotate'));
    }

    function clearModalContent() {
        fields.innerHTML = '';
    }

    document.querySelectorAll('.toggle-review').forEach(button => {
    button.addEventListener('click', async () => {
        const orderId = button.dataset.orderId;
        const arrow = button.querySelector('.arrow');

        try {
            const modal = document.getElementById('review-modal');
            const fields = document.getElementById('product-review-fields');
            const reviewForm = document.getElementById('review-form');
            const cancelBtn = document.getElementById('cancel-btn');
            const submitBtn = reviewForm.querySelector('button[type="submit"]');

            fields.innerHTML = '';
            reviewForm.action = `/orders/${orderId}/rate`;

            const res = await fetch(`/orders/${orderId}/ratings`);
            const ratings = await res.json();

            const hasRating = ratings.some(item => item.rating || item.review);

            if (hasRating) {
                ratings.forEach(item => {
                    const section = document.createElement('div');
                    section.classList.add('mb-4');

                    const starsHtml = Array.from({ length: 5 }, (_, i) => `
                        <span class="${i < item.rating ? 'text-yellow-400' : 'text-gray-400'} text-xl">&#9733;</span>
                    `).join('');

                    section.innerHTML = `
                        <label class="font-semibold block mb-1">${item.name}</label>
                        <div class="mb-2">${starsHtml}</div>
                        <div class="italic">${item.review || ''}</div>
                    `;
                    fields.appendChild(section);
                });

                cancelBtn.innerText = 'Close';
                submitBtn.classList.add('hidden');
                document.getElementById('username').parentElement.classList.add('hidden');
            } else {
                const productRes = await fetch(`/orders/${orderId}/products`);
                const products = await productRes.json();

                cancelBtn.innerText = 'Cancel';
                submitBtn.classList.remove('hidden');
                document.getElementById('username').parentElement.classList.remove('hidden');

                products.forEach(product => {
                    const section = document.createElement('div');
                    section.classList.add('mb-4');

                    const starsHtml = Array.from({ length: 5 }, (_, i) => `
                        <span class="star text-gray-400 cursor-pointer text-xl" data-value="${i + 1}" data-product-id="${product.id}">&#9733;</span>
                    `).join('');

                    section.innerHTML = `
                        <label class="font-semibold block mb-1">${product.name}</label>
                        <div class="rating mb-2" data-product-id="${product.id}">
                            ${starsHtml}
                        </div>
                        <input type="hidden" name="rating[${product.id}]" id="rating-input-${product.id}">
                        <textarea name="review[${product.id}]" class="w-full border rounded p-2" placeholder="Write a review (optional)"></textarea>
                    `;
                    fields.appendChild(section);
                });
            }

            modal.classList.remove('hidden');
        } catch (err) {
            console.error('Error showing modal:', err);
            alert('An error occurred while loading the review form.');
        }
    });
});


    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('star')) {
            const value = parseInt(e.target.dataset.value);
            const productId = e.target.dataset.productId;
            document.getElementById(`rating-input-${productId}`).value = value;

            const stars = document.querySelectorAll(`.rating[data-product-id="${productId}"] .star`);
            stars.forEach((star, index) => {
                star.classList.toggle('text-yellow-400', index < value);
                star.classList.toggle('text-gray-400', index >= value);
            });
        }
    });

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
            resetAllArrows();
        }
    });

    document.getElementById('close-thank-you').addEventListener('click', () => {
        thankYouModal.classList.add('hidden');
        modal.classList.add('hidden');
        resetAllArrows();
    });

    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        resetAllArrows();
    });
});
