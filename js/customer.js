document.addEventListener('DOMContentLoaded', () => {
    // Add to cart form handling
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const productId = form.dataset.productId;
            const quantityInput = form.querySelector('input[name="quantity"]');
            
            if (!productId || !quantityInput) {
                showError(form, 'Form configuration error. Please try again.');
                return;
            }

            const quantity = parseInt(quantityInput.value, 10);
            const stock = parseInt(quantityInput.dataset.stock || 0, 10);

            if (isNaN(quantity) || quantity < 1 || quantity > stock || !Number.isInteger(quantity)) {
                showError(quantityInput, `Please enter a whole number between 1 and ${stock}.`);
                return;
            }

            if (confirm('Add this product to your cart?')) {
                try {
                    const url = `php/customer/cart_handler.php?action=add&product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`;
                    const response = await fetch(url, { method: 'GET' });
                    if (response.ok) {
                        window.location.href = 'cart.php';
                    } else {
                        throw new Error('Failed to add to cart');
                    }
                } catch (error) {
                    showError(form, 'Failed to add to cart. Please try again.');
                }
            }
        });

        const quantityInput = form.querySelector('input[name="quantity"]');
        if (quantityInput) {
            quantityInput.addEventListener('input', () => {
                clearError(quantityInput);
            });
        }
    });

    // Remove item confirmation using event delegation
    document.body.addEventListener('click', (e) => {
        const button = e.target.closest('.remove-btn');
        if (button) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
            }
        }
    });

    // Quantity form validation (for cart page)
    const quantityForms = document.querySelectorAll('.quantity-form');
    quantityForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const quantityInput = form.querySelector('input[name="quantity"]');
            if (quantityInput) {
                const quantity = parseInt(quantityInput.value, 10);
                const stock = parseInt(quantityInput.dataset.stock || 0, 10);
                if (isNaN(quantity) || quantity < 1 || quantity > stock || !Number.isInteger(quantity)) {
                    e.preventDefault();
                    showError(quantityInput, `Please enter a whole number between 1 and ${stock}.`);
                }
            }
        });

        const quantityInput = form.querySelector('input[name="quantity"]');
        if (quantityInput) {
            quantityInput.addEventListener('input', () => clearError(quantityInput));
        }
    });

    // Search form validation
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="search"]');
        if (searchInput) {
            searchForm.addEventListener('submit', (e) => {
                const value = searchInput.value.trim();
                if (value.length > 0 && value.length < 2) {
                    e.preventDefault();
                    showError(searchInput, 'Search term must be at least 2 characters.');
                }
            });
            searchInput.addEventListener('input', () => clearError(searchInput));
        }
    }

    // Smooth scroll to product grid
    const productGrid = document.querySelector('.product-grid');
    if (productGrid) {
        productGrid.scrollIntoView({ behavior: 'smooth' });
    }

    // Dynamic category filter highlight
    const categorySelect = document.querySelector('select[name="category_id"]');
    if (categorySelect) {
        categorySelect.addEventListener('change', () => {
            categorySelect.style.borderColor = categorySelect.value ? '#28a745' : '#ddd';
        });
    }

    // Khalti button loading state
    const checkoutForm = document.querySelector('.payment-form form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', (e) => {
            const btn = checkoutForm.querySelector('.khalti-btn');
            if (btn && !e.defaultPrevented) { // Only proceed if form validation passes
                btn.classList.add('loading');
                btn.disabled = true;
            }
        });
    }

    // Check cart status on checkout page
    const orderSummary = document.querySelector('.order-summary');
    if (orderSummary && window.location.pathname.includes('checkout.php')) {
        fetch('php/customer/check_cart.php')
            .then(response => response.json())
            .then(data => {
                if (!data.hasItems) {
                    window.location.href = 'cart.php';
                }
            })
            .catch(error => {
                showError(orderSummary, 'Failed to verify cart. Please refresh the page.');
            });
    }

    // Error handling functions
    function showError(input, message) {
        clearError(input);
        const error = document.createElement('div');
        error.className = 'error';
        error.textContent = message;
        error.style.color = '#dc3545';
        error.style.marginTop = '0.5em';
        error.style.fontSize = '0.9em';
        input.parentElement.appendChild(error);
        input.style.borderColor = '#dc3545';
    }

    function clearError(input) {
        const error = input.parentElement.querySelector('.error');
        if (error) {
            error.remove();
        }
        input.style.borderColor = '#ddd';
    }
});