document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const isLoggedIn = document.body.dataset?.loggedIn === 'true' || false;

    // Quantity change handler
    document.querySelectorAll('.cart-quantity').forEach(input => {
        input.addEventListener('change', function() {
            const id = this.dataset.id;
            const quantity = Math.max(1, parseInt(this.value)); // Ensure quantity >= 1
            const row = this.closest('tr');

            fetch(window.cartRoutes.update, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id: id,
                    quantity: quantity,
                    is_logged_in: isLoggedIn
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    row.querySelector('.item-total').textContent = '$' + data.subtotal;
                    document.getElementById('cart-total').textContent = '$' + data.total;
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
                this.value = this.dataset.oldValue || 1;
                location.reload();
            });
        });
    });

    // Item removal handler
    const removeConfirmModal = document.getElementById('remove-confirm-modal');
    const successModal = document.getElementById('cart-success-modal');
    
    // Track which item we're about to remove
    let currentItemToRemove = null;
    
    // Remove item click handler - 
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', (e) => {
            currentItemToRemove = {
                id: e.target.dataset.id,
                element: document.getElementById(`cart-item-${e.target.dataset.id}`),
                button: e.target
            };
            removeConfirmModal.classList.remove('hidden');
        });
    });
    
    // Confirm removal button handler
    document.getElementById('confirm-remove').addEventListener('click', async () => {
        if (!currentItemToRemove) return;
        
        const { id, element, button } = currentItemToRemove;
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        try {
            const response = await fetch(window.cartRoutes.remove, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json' // Explicitly request JSON
                },
                body: JSON.stringify({ id })
            });
            
            const data = await response.json();
            
            if (!response.ok) throw new Error(data.message || 'Removal failed');
            
            // Animate removal
            element.style.opacity = '0';
            await new Promise(resolve => setTimeout(resolve, 300));
            
            element.remove();
            document.getElementById('cart-total').textContent = '$' + data.total;
            
            // Show success modal
            document.getElementById('success-message').textContent = 'Item removed from cart';
            removeConfirmModal.classList.add('hidden');
            successModal.classList.remove('hidden');
            
            // Check if cart is empty
            if (data.cart_empty) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('success-message').textContent = error.message;
            removeConfirmModal.classList.add('hidden');
            successModal.classList.remove('hidden');
        } finally {
            button.disabled = false;
            button.innerHTML = 'Remove';
            currentItemToRemove = null;
        }
    });
    
    // Cancel removal button handler
    document.getElementById('cancel-remove').addEventListener('click', () => {
        removeConfirmModal.classList.add('hidden');
        currentItemToRemove = null;
    });
    
    // Close success modal handler (shared with buyer.js pattern)
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            successModal.classList.add('hidden');
        });
    });

    // handle Auth for guest (checkout button)
    const proceedBtn = document.getElementById('proceed-to-checkout');
    const authModal = document.getElementById('auth-required-modal');
    const checkoutModal = document.getElementById('checkout-modal');
    
    if (proceedBtn) {
        proceedBtn.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#') {
                // Guest flow
                e.preventDefault();
                document.getElementById('auth-required-modal').classList.remove('hidden');
            } else {
                // Logged-in user flow
                e.preventDefault();
                document.getElementById('checkout-modal').classList.remove('hidden');
            }
        });
    }
    
    // Modal close handlers
    document.getElementById('cancel-auth')?.addEventListener('click', function() {
        document.getElementById('auth-required-modal').classList.add('hidden');
    });
    
    // handle the form submission from the checkout modal
    const checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitBtn = document.getElementById('submit-order');
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Processing...';

                try {
                    const formData = new FormData(checkoutForm);
                    const response = await fetch(window.checkoutRoutes.store, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    
                    if (!response.ok) throw new Error(data.message || 'Checkout failed');

                    // Success - show confirmation
                    document.getElementById('checkout-modal').classList.add('hidden');
                    document.getElementById('order-success-modal').classList.remove('hidden');
                } catch (error) {
                    console.error('Checkout error:', error);
                    alert('Checkout failed: ' + error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Place My Order';
                }
            });
        }

        // Handle success modal close
        document.getElementById('go-home')?.addEventListener('click', () => {
            window.location.href = "{{ route('home') }}";
        });

        // Handle modal cancel button
        document.getElementById('cancel-checkout')?.addEventListener('click', () => {
            document.getElementById('checkout-modal').classList.add('hidden');
        });

    // Close when clicking outside
    document.getElementById('auth-required-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});