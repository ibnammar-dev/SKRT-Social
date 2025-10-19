import { Controller } from '@hotwired/stimulus';

/* 
 * Form Controller
 * 
 * Handles form submission states and validation
 * 
 * Usage:
 * <form data-controller="form"
 *       data-action="turbo:submit-start->form#disableSubmit 
 *                    turbo:submit-end->form#enableSubmit">
 *   <input type="text" required>
 *   <button type="submit" data-form-target="submit">Submit</button>
 * </form>
 */
export default class extends Controller {
    static targets = ['submit'];
    static values = {
        submittingText: { type: String, default: 'Submitting...' }
    };

    connect() {
        this.originalButtonText = this.hasSubmitTarget ? this.submitTarget.innerHTML : null;
    }

    disableSubmit() {
        if (!this.hasSubmitTarget) return;

        this.submitTarget.disabled = true;
        this.submitTarget.innerHTML = `
            <i class="fas fa-spinner fa-spin me-2"></i>
            ${this.submittingTextValue}
        `;
    }

    enableSubmit() {
        if (!this.hasSubmitTarget) return;

        this.submitTarget.disabled = false;
        if (this.originalButtonText) {
            this.submitTarget.innerHTML = this.originalButtonText;
        }
    }

    // Reset form after successful submission
    reset() {
        this.element.reset();
        // Dispatch event for other controllers
        this.element.dispatchEvent(new CustomEvent('form:reset'));
    }
}

