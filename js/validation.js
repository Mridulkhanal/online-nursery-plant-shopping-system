function validateRegisterForm() {
    const form = document.querySelector('form');
    const name = form.querySelector('input[name="name"]');
    const email = form.querySelector('input[name="email"]');
    const password = form.querySelector('input[name="password"]');
    const confirm_password = form.querySelector('input[name="confirm_password"]');

    let isValid = true;

    clearError(name);
    clearError(email);
    if (password) clearError(password);

    if (!/^[a-zA-Z\s]+$/.test(name.value.trim())) {
        showError(name, 'Name can only contain letters and spaces.');
        isValid = false;
    }
    if (!/^[a-zA-Z][a-zA-Z0-9._-]*@[a-zA-Z]+\.[a-zA-Z]+$/.test(email.value.trim())) {
        showError(email, 'Email must start with a letter, contain one @, and have a domain with only letters (e.g., john@domain.com).');
        isValid = false;
    }
    if (password && password.value.length < 6) {
        showError(password, 'Password must be at least 6 characters.');
        isValid = false;
    }
    if (password && confirm_password && password.value !== confirm_password.value) {
        showError(password, 'Password must be the same as confirm password.');
        isValid = false;
    }
    return isValid;
}

function validateLoginForm() {
    const form = document.querySelector('form');
    const email = form.querySelector('input[name="email"]');
    const password = form.querySelector('input[name="password"]');
    let isValid = true;

    clearError(email);
    clearError(password);

    if (!/^[a-zA-Z0-9][a-zA-Z0-9._%+-]{0,62}[a-zA-Z0-9]@[a-zA-Z]+\.[a-zA-Z]{2,63}$/.test(email.value)) {
        showError(email, 'Please enter a valid email address (e.g., mridulkhanal12@gmail.com).');
        isValid = false;
    }
    if (password.value.length < 6) {
        showError(password, 'Password must be at least 6 characters.');
        isValid = false;
    }

    return isValid;
}

function validateCheckoutForm() {
    const form = document.querySelector('form');
    const deliveryAddress = form.querySelector('textarea[name="delivery_address"]');
    let isValid = true;

    clearError(deliveryAddress);
    if (!deliveryAddress.value.trim()) {
        showError(deliveryAddress, 'Delivery address is required.');
        isValid = false;
    }
    return isValid;
}

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

function validateContactForm() {
    const form = document.querySelector('form');
    const name = form.querySelector('input[name="name"]');
    const email = form.querySelector('input[name="email"]');
    let isValid = true;

    clearError(name);
    clearError(email);

    if (!/^[a-zA-Z\s]+$/.test(name.value.trim())) {
        showError(name, 'Name can only contain letters and spaces.');
        isValid = false;
    }

    if (!/^[a-zA-Z][a-zA-Z0-9._-]*@[a-zA-Z]+\.[a-zA-Z]+$/.test(email.value.trim())) {
        showError(email, 'Email must start with a letter, contain one @, and have a domain with only letters (e.g., john@domain.com).');
        isValid = false;
    }

    return isValid;
}

function setupRealTimeValidation() {
    const form = document.querySelector('form');
    if (!form) return;

    const name = form.querySelector('input[name="name"]');
    const email = form.querySelector('input[name="email"]');
    const deliveryAddress = form.querySelector('textarea[name="delivery_address"]');

    if (name) {
        name.addEventListener('input', () => {
            clearError(name);
            if (name.value.trim() && !/^[a-zA-Z\s]+$/.test(name.value.trim())) {
                showError(name, 'Name can only contain letters and spaces.');
            }
        });
    }

    if (email) {
        email.addEventListener('input', () => {
            clearError(email);
            if (email.value.trim() && !/^[a-zA-Z][a-zA-Z0-9._-]*@[a-zA-Z]+\.[a-zA-Z]+$/.test(email.value.trim())) {
                showError(email, 'Email must start with a letter, contain one @, and have a domain with only letters (e.g., mridul@domain.com).');
            }
        });
    }

    if (deliveryAddress) {
        deliveryAddress.addEventListener('input', () => {
            clearError(deliveryAddress);
            if (deliveryAddress.value.trim() === '') {
                showError(deliveryAddress, 'Delivery address is required.');
            }
        });
    }
}

window.onload = setupRealTimeValidation;