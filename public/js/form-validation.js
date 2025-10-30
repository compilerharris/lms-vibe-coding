// Real-time Form Validation
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation to all forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        addRealTimeValidation(form);
    });
});

function addRealTimeValidation(form) {
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        // Add validation on blur
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Add validation on input for immediate feedback
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
}

function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    const fieldType = field.type;
    
    // Clear previous validation
    field.classList.remove('is-valid', 'is-invalid');
    removeFieldError(field);
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'This field is required.');
        return false;
    }
    
    // Email validation
    if (fieldType === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Please enter a valid email address.');
            return false;
        }
    }
    
    // Phone validation
    if (fieldName === 'phone' && value) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        if (!phoneRegex.test(value)) {
            showFieldError(field, 'Please enter a valid phone number.');
            return false;
        }
    }
    
    // Number validation
    if (fieldType === 'number' && value) {
        const num = parseInt(value);
        const min = field.getAttribute('min');
        const max = field.getAttribute('max');
        
        if (isNaN(num)) {
            showFieldError(field, 'Please enter a valid number.');
            return false;
        }
        
        if (min && num < parseInt(min)) {
            showFieldError(field, `Value must be at least ${min}.`);
            return false;
        }
        
        if (max && num > parseInt(max)) {
            showFieldError(field, `Value must be at most ${max}.`);
            return false;
        }
    }
    
    // If we get here, field is valid
    if (value) {
        field.classList.add('is-valid');
    }
    
    return true;
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    // Remove existing error message
    removeFieldError(field);
    
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function removeFieldError(field) {
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
}

// Check for duplicate values in real-time
function checkDuplicates(field, endpoint) {
    const value = field.value.trim();
    if (!value) return;
    
    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            field: field.name,
            value: value,
            exclude_id: field.dataset.excludeId || null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            showFieldError(field, `${field.name} already exists. Please choose a different value.`);
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            removeFieldError(field);
        }
    })
    .catch(error => {
        console.error('Validation error:', error);
    });
}

// Global form submission handler
document.addEventListener('submit', function(e) {
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    
    // Skip if it's a logout form, login form, or has data-skip-loader attribute
    if (form.id === 'logout-form' || form.hasAttribute('data-skip-loader') || form.action.includes('login')) {
        return;
    }
    
    if (submitBtn) {
        // Show loading state
        showButtonLoader(submitBtn);
        
        // Validate all fields before submission
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            hideButtonLoader(submitBtn);
            showTopAlert('Please fix the errors below before submitting.', 'danger');
        } else {
            // Show full page loader for valid form submissions
            if (window.showFullPageLoader) {
                let message = 'Processing...';
                const buttonText = submitBtn.textContent.trim();
                if (buttonText.includes('Create') || buttonText.includes('Add')) {
                    message = 'Creating...';
                } else if (buttonText.includes('Update') || buttonText.includes('Edit')) {
                    message = 'Updating...';
                } else if (buttonText.includes('Delete')) {
                    message = 'Deleting...';
                }
                window.showFullPageLoader(message);
            }
        }
    }
});

function showTopAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.top-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show top-alert`;
    alertDiv.style.cssText = 'position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function showButtonLoader(button) {
    const originalText = button.innerHTML;
    button.setAttribute('data-original-text', originalText);
    button.disabled = true;
    button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Processing...
    `;
}

function hideButtonLoader(button) {
    const originalText = button.getAttribute('data-original-text');
    if (originalText) {
        button.innerHTML = originalText;
        button.disabled = false;
    }
}
