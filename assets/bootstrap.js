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

// Handle flash messages on navigation
document.addEventListener('turbo:load', () => {
    // Auto-dismiss flash messages
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach((message) => {
        setTimeout(() => {
            message.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });
});

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
