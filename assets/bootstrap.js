import { startStimulusApp } from '@symfony/stimulus-bundle';
import '@hotwired/turbo';

// Start Stimulus
const app = startStimulusApp();

// Configure Turbo
import * as Turbo from '@hotwired/turbo';

// Enable Turbo Drive (default is on, but being explicit)
Turbo.start();

// Optional: Configure Turbo to handle form submissions
document.addEventListener('turbo:before-fetch-request', (event) => {
    // Add CSRF token to Turbo requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (csrfToken) {
        event.detail.fetchOptions.headers['X-CSRF-TOKEN'] = csrfToken;
    }
});

// Handle flash messages - auto-dismiss after delay
function autoDismissToast(toast) {
    // Prevent multiple timeouts on same toast
    if (toast.dataset.dismissing) return;
    toast.dataset.dismissing = 'true';
    
    setTimeout(() => {
        if (toast && toast.parentElement) {
            toast.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000); // 5 seconds
}

// Setup observer for new toasts (only once)
let observerSetup = false;
function setupToastObserver() {
    if (observerSetup) return;
    
    const container = document.getElementById('flash-container');
    if (!container) return;
    
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1 && node.classList?.contains('toast-notification')) {
                    autoDismissToast(node);
                }
            });
        });
    });
    
    observer.observe(container, { childList: true });
    observerSetup = true;
}

// Handle toasts on page load
document.addEventListener('turbo:load', () => {
    // Dismiss existing toasts
    const toasts = document.querySelectorAll('.toast-notification:not([data-dismissing])');
    toasts.forEach(autoDismissToast);
    
    // Setup observer if not already done
    setupToastObserver();
});

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
