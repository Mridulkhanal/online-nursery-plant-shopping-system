document.addEventListener('DOMContentLoaded', () => {
    // Confirm delete action
    const deleteLinks = document.querySelectorAll('a[href*="delete_product.php"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to delete this product?')) {
                e.preventDefault();
            }
        });
    });

    // Validate product form (add/edit)
    const productForms = document.querySelectorAll('form[action*="add_product.php"], form[action*="edit_product.php"]');
    productForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const name = form.querySelector('input[name="name"]');
            const price = form.querySelector('input[name="price"]');
            const description = form.querySelector('textarea[name="description"]');
            const stock = form.querySelector('input[name="stock"]');
            let isValid = true;

            clearError(name);
            clearError(price);
            clearError(description);
            clearError(stock);

            if (name.value.trim().length < 3) {
                showError(name, 'Product name must be at least 3 characters.');
                isValid = false;
            }
            if (price.value <= 0) {
                showError(price, 'Price must be greater than 0.');
                isValid = false;
            }
            if (description.value.trim().length < 10) {
                showError(description, 'Description must be at least 10 characters.');
                isValid = false;
            }
            if (stock.value < 0 || isNaN(stock.value)) {
                showError(stock, 'Stock quantity must be a non-negative number.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    // Confirm inquiry response
    const inquiryForms = document.querySelectorAll('form[action*="respond_inquiry.php"]');
    inquiryForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const response = form.querySelector('textarea[name="response"]');
            clearError(response);
            if (response.value.trim().length < 5) {
                showError(response, 'Response must be at least 5 characters.');
                e.preventDefault();
            }
        });
    });

    // Live update for orders table
    function fetchOrders() {
        fetch('../php/fetch_orders.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#orders-table tbody');
                if (!tbody) return; // Ensure tbody exists
                tbody.innerHTML = '';
                data.forEach(order => {
                    const status = order.status ? order.status.charAt(0).toUpperCase() + order.status.slice(1).toLowerCase() : 'Pending';
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${order.id}</td>
                        <td>${order.customer_name}</td>
                        <td>${order.products}</td>
                        <td>${order.total_quantity}</td>
                        <td>${order.delivery_address}</td>
                        <td>Rs.${parseFloat(order.total_amount).toFixed(2)}</td>
                        <td>${order.status}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching orders:', error));
    }

    // Initial fetch and set interval for live updates, except on admin_orders.php
    if (!window.location.pathname.includes('admin_orders.php')) {
        fetchOrders();
        setInterval(fetchOrders, 30000); // Update every 30 seconds
    }

    // Theme switching
    const themeSwitcher = document.querySelector('.theme-switcher');
    if (themeSwitcher) {
        console.log('Theme switcher found:', themeSwitcher);
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        document.documentElement.setAttribute('data-theme', currentTheme);
        themeSwitcher.textContent = currentTheme === 'light' ? 'Switch to Dark Mode' : 'Switch to Light Mode';

        themeSwitcher.addEventListener('click', () => {
            const newTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            themeSwitcher.textContent = newTheme === 'light' ? 'Switch to Dark Mode' : 'Switch to Light Mode';
            console.log('Theme switched to:', newTheme);
        });
    } else {
        console.error('Theme switcher element not found!');
    }

    // Helper functions for error handling
    function showError(input, message) {
        clearError(input);
        const error = document.createElement('div');
        error.className = 'error';
        error.textContent = message;
        input.after(error);
        input.style.borderColor = 'red';
    }

    function clearError(input) {
        const error = input.nextElementSibling;
        if (error && error.className === 'error') {
            error.remove();
        }
        input.style.borderColor = '#ddd';
    }
});