function validateContactForm() {
    let name = document.getElementById('name');
    let email = document.getElementById('email');
    let message = document.getElementById('message');
    let isValid = true;

    clearError(name);
    clearError(email);
    clearError(message);

    if (!name.value.trim()) {
        showError(name, 'Name is required.');
        isValid = false;
    } else if (name.value.trim().length < 2) {
        showError(name, 'Name must be at least 2 characters.');
        isValid = false;
    }

    if (!email.value.trim()) {
        showError(email, 'Email is required.');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        showError(email, 'Please enter a valid email address.');
        isValid = false;
    }

    if (!message.value.trim()) {
        showError(message, 'Message is required.');
        isValid = false;
    } else if (message.value.trim().length < 10) {
        showError(message, 'Message must be at least 10 characters.');
        isValid = false;
    }

    return isValid;
}