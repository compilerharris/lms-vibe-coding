/**
 * Full Page Loader with Blur Background
 * Shows loader on form submissions and page navigation
 */

class FullPageLoader {
    constructor() {
        this.loader = document.getElementById('full-page-loader');
        this.init();
    }

    init() {
        // Show loader on form submissions
        this.attachFormListeners();
        
        // Show loader on navigation links
        this.attachNavigationListeners();
    }

    show(message = 'Processing...') {
        if (this.loader) {
            const loaderText = this.loader.querySelector('.loader-text');
            if (loaderText) {
                loaderText.textContent = message;
            }
            this.loader.classList.add('show');
            
            // Auto-hide after 10 seconds as a safety measure
            setTimeout(() => {
                this.hide();
            }, 10000);
        }
    }

    hide() {
        if (this.loader) {
            this.loader.classList.remove('show');
        }
    }

    attachFormListeners() {
        // Listen for form submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            
            // Skip if it's a logout form, login form, or has data-skip-loader attribute
            if (form.id === 'logout-form' || form.hasAttribute('data-skip-loader') || form.action.includes('login')) {
                return;
            }

            // Show loader with appropriate message
            let message = 'Processing...';
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                const buttonText = submitButton.textContent.trim();
                if (buttonText.includes('Create') || buttonText.includes('Add')) {
                    message = 'Creating...';
                } else if (buttonText.includes('Update') || buttonText.includes('Edit')) {
                    message = 'Updating...';
                } else if (buttonText.includes('Delete')) {
                    message = 'Deleting...';
                } else if (buttonText.includes('Sign In') || buttonText.includes('Login')) {
                    return; // Skip login forms
                }
            }

            this.show(message);
        });

        // Hide loader when page loads (for form redirects)
        window.addEventListener('load', () => {
            this.hide();
        });
        
        // Hide loader on page unload
        window.addEventListener('beforeunload', () => {
            this.hide();
        });
    }

    attachNavigationListeners() {
        // Listen for navigation clicks
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && this.isInternalLink(link)) {
                this.show('Loading...');
            }
        });
    }

    isInternalLink(link) {
        const href = link.getAttribute('href');
        if (!href) return false;
        
        // Skip if it's a logout link or has data-skip-loader attribute
        if (link.hasAttribute('data-skip-loader') || href.includes('logout')) {
            return false;
        }

        // Check if it's an internal link
        return href.startsWith('/') || href.includes(window.location.hostname);
    }
}

// Initialize the full page loader when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.fullPageLoader = new FullPageLoader();
    
    // Add keyboard shortcut to force hide all loaders (Ctrl+Shift+L)
    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey && e.shiftKey && e.key === 'L') {
            e.preventDefault();
            window.hideAllLoaders();
            console.log('All loaders force hidden');
        }
    });
});

// Global functions for manual control
window.showFullPageLoader = function(message) {
    if (window.fullPageLoader) {
        window.fullPageLoader.show(message);
    }
};

window.hideFullPageLoader = function() {
    if (window.fullPageLoader) {
        window.fullPageLoader.hide();
    }
};

// Force hide all loaders
window.hideAllLoaders = function() {
    // Hide full page loader
    if (window.fullPageLoader) {
        window.fullPageLoader.hide();
    }
    
    // Hide page loader
    const pageLoader = document.getElementById('page-loader');
    if (pageLoader) {
        pageLoader.style.display = 'none';
    }
    
    // Reset all button loaders
    const loadingButtons = document.querySelectorAll('.btn-loading');
    loadingButtons.forEach(button => {
        if (window.hideButtonLoader) {
            window.hideButtonLoader(button);
        }
    });
};

// Debug function to check loader states
window.debugLoaders = function() {
    console.log('=== Loader Debug Info ===');
    console.log('Full page loader exists:', !!window.fullPageLoader);
    console.log('Full page loader visible:', window.fullPageLoader?.loader?.classList.contains('show'));
    console.log('Page loader visible:', document.getElementById('page-loader')?.style.display !== 'none');
    console.log('Loading buttons:', document.querySelectorAll('.btn-loading').length);
    console.log('========================');
};
