// Page Loader for CTA Buttons
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to all forms (except login and logout)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        // Skip login and logout forms
        if (form.action.includes('login') || form.id === 'logout-form') {
            return;
        }
        
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                showButtonLoader(submitBtn);
            }
        });
    });

    // Add loading state to all links that look like buttons
    const ctaLinks = document.querySelectorAll('a.btn');
    ctaLinks.forEach(link => {
        // Skip logout links and external links
        if (!link.href.includes('logout') && !link.href.startsWith('http')) {
            link.addEventListener('click', function(e) {
                showButtonLoader(this);
            });
        }
    });

    // Add loading state to delete buttons
    const deleteButtons = document.querySelectorAll('button[type="submit"]');
    deleteButtons.forEach(button => {
        if (button.closest('form') && button.innerHTML.includes('trash')) {
            button.addEventListener('click', function(e) {
                if (confirm('Are you sure you want to delete this item?')) {
                    showButtonLoader(this);
                } else {
                    e.preventDefault();
                }
            });
        }
    });
});

function showButtonLoader(button) {
    const originalText = button.innerHTML;
    const originalDisabled = button.disabled;
    
    // Store original content
    button.setAttribute('data-original-text', originalText);
    button.setAttribute('data-original-disabled', originalDisabled);
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Loading...
    `;
    
    // Add loading class for styling
    button.classList.add('btn-loading');
}

function hideButtonLoader(button) {
    const originalText = button.getAttribute('data-original-text');
    const originalDisabled = button.getAttribute('data-original-disabled') === 'true';
    
    if (originalText) {
        button.innerHTML = originalText;
        button.disabled = originalDisabled;
        button.classList.remove('btn-loading');
    }
}

// Global function to show page loader
function showPageLoader() {
    const loader = document.getElementById('page-loader');
    if (loader) {
        loader.style.display = 'flex';
    }
}

// Global function to hide page loader
function hidePageLoader() {
    const loader = document.getElementById('page-loader');
    if (loader) {
        loader.style.display = 'none';
    }
}

// Show loader on page navigation
document.addEventListener('click', function(e) {
    const target = e.target.closest('a');
    if (target && target.classList.contains('btn') && !target.href.includes('logout') && !target.href.startsWith('http')) {
        showPageLoader();
    }
});

// Hide loader when page is fully loaded
window.addEventListener('load', function() {
    hidePageLoader();
});

// Make hideButtonLoader globally available
window.hideButtonLoader = hideButtonLoader;
