import { Controller } from '@hotwired/stimulus';

/* 
 * Modal Controller
 * 
 * Manages Bootstrap modals with Turbo support
 * Handles confirmation dialogs for delete actions
 */
export default class extends Controller {
    static values = {
        id: String,
        url: String,
        title: String,
        message: String
    };

    connect() {
        // Store reference if needed
        if (this.hasIdValue) {
            this.modalElement = document.getElementById(this.idValue);
        }
    }

    confirmDelete(event) {
        event.preventDefault();
        
        const url = this.urlValue;
        const title = this.titleValue || 'Confirm Delete';
        const message = this.messageValue || 'Are you sure you want to delete this item?';
        
        // Create modal dynamically
        const modalHtml = `
            <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>${message}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if present
        const existingModal = document.getElementById('deleteConfirmModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modalElement = document.getElementById('deleteConfirmModal');
        const modal = new bootstrap.Modal(modalElement);
        
        // Handle confirm button click
        document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
            try {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                
                // Make DELETE request using fetch with Turbo Stream accept header
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'text/vnd.turbo-stream.html, text/html, application/xhtml+xml'
                    }
                });
                
                if (response.ok) {
                    const contentType = response.headers.get('Content-Type');
                    if (contentType && contentType.includes('text/vnd.turbo-stream.html')) {
                        // Handle Turbo Stream response
                        const html = await response.text();
                        Turbo.renderStreamMessage(html);
                    }
                    modal.hide();
                } else {
                    console.error('Delete failed:', response.statusText);
                    alert('Failed to delete. Please try again.');
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('An error occurred. Please try again.');
            }
        });
        
        // Clean up modal when hidden
        modalElement.addEventListener('hidden.bs.modal', () => {
            modalElement.remove();
        });
        
        modal.show();
    }

    open(event) {
        event.preventDefault();
        const modalId = event.params?.target || this.idValue;
        const modalElement = document.getElementById(modalId);
        
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    close(event) {
        event.preventDefault();
        const modalElement = event.currentTarget.closest('.modal');
        
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
    }
}

