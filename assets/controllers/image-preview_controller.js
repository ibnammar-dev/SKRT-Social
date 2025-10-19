import { Controller } from '@hotwired/stimulus';

/* 
 * Image Preview Controller
 * 
 * Handles image upload previews with drag-and-drop support
 * Works with upload-area styled components
 */
export default class extends Controller {
    static targets = ['input', 'preview', 'previewContainer', 'uploadPrompt'];

    // Open file dialog when clicking upload area
    openFileDialog(event) {
        // Don't trigger if clicking on the remove button
        if (event.target.closest('.btn-remove-image')) {
            return;
        }
        this.inputTarget.click();
    }

    // Handle file selection
    handleFileSelect(event) {
        this.displayPreview(this.inputTarget.files);
    }

    // Drag and drop handlers
    highlight(event) {
        event.preventDefault();
        event.stopPropagation();
        this.element.classList.add('dragover');
    }

    unhighlight(event) {
        event.preventDefault();
        event.stopPropagation();
        this.element.classList.remove('dragover');
    }

    handleDrop(event) {
        event.preventDefault();
        event.stopPropagation();
        this.element.classList.remove('dragover');
        
        const files = event.dataTransfer.files;
        if (files.length > 0) {
            // Transfer files to the input element
            this.inputTarget.files = files;
            this.displayPreview(files);
        }
    }

    // Display the image preview
    displayPreview(files) {
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewTarget.src = e.target.result;
                    this.previewContainerTarget.classList.remove('d-none');
                    this.uploadPromptTarget.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            }
        }
    }

    // Remove the image
    removeImage(event) {
        event.preventDefault();
        event.stopPropagation();
        this.inputTarget.value = '';
        this.previewContainerTarget.classList.add('d-none');
        this.uploadPromptTarget.classList.remove('d-none');
    }
}

