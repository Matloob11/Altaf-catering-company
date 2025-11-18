/**
 * Altaf Catering - Form Validation & Handler
 * Handles validation and submission for Booking and Contact forms
 */

class FormValidator {
    constructor() {
        this.formConfig = {
            booking: {
                fields: {
                    name: { required: true, minLength: 3 },
                    email: { required: true, pattern: 'email' },
                    phone: { required: true, pattern: 'phone' },
                    eventDate: { required: true, pattern: 'date' },
                    eventTime: { required: true },
                    guestCount: { required: true, minValue: 10, maxValue: 5000 },
                    eventType: { required: true },
                    message: { minLength: 10 }
                },
                endpoint: 'api/booking' // Replace with actual endpoint
            },
            contact: {
                fields: {
                    name: { required: true, minLength: 3 },
                    email: { required: true, pattern: 'email' },
                    phone: { required: true, pattern: 'phone' },
                    subject: { required: true, minLength: 5 },
                    message: { required: true, minLength: 10 }
                },
                endpoint: 'api/contact'
            }
        };

        this.init();
    }

    init() {
        // Initialize booking form
        const bookingForm = document.getElementById('bookingForm');
        if (bookingForm) {
            bookingForm.addEventListener('submit', (e) => this.handleSubmit(e, 'booking'));
        }

        // Initialize contact form
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => this.handleSubmit(e, 'contact'));
        }

        // Add real-time validation
        this.attachRealTimeValidation();
    }

    attachRealTimeValidation() {
        document.querySelectorAll('[data-validate]').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });
    }

    validateField(field) {
        const fieldName = field.getAttribute('name');
        const fieldValue = field.value.trim();
        const validation = field.getAttribute('data-validate');

        if (!validation) return true;

        const rules = validation.split('|');
        for (let rule of rules) {
            const [ruleName, ...params] = rule.split(':');
            if (!this.applyRule(ruleName, fieldValue, params)) {
                this.showFieldError(field, this.getRuleMessage(ruleName, fieldName));
                return false;
            }
        }
        this.clearFieldError(field);
        return true;
    }

    applyRule(rule, value, params) {
        switch (rule) {
            case 'required':
                return value !== '';
            case 'email':
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            case 'phone':
                return /^[\d\s\-\+\(\)]{10,}$/.test(value.replace(/\s/g, ''));
            case 'min':
                return value.length >= parseInt(params[0]);
            case 'max':
                return value.length <= parseInt(params[0]);
            case 'minValue':
                return parseInt(value) >= parseInt(params[0]);
            case 'maxValue':
                return parseInt(value) <= parseInt(params[0]);
            case 'date':
                const selectedDate = new Date(value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                return selectedDate >= today;
            default:
                return true;
        }
    }

    getRuleMessage(rule, fieldName) {
        const messages = {
            'required': `${fieldName} is required`,
            'email': 'Please enter a valid email address',
            'phone': 'Please enter a valid phone number',
            'min': `${fieldName} must be at least 3 characters`,
            'max': `${fieldName} is too long`,
            'minValue': `Please enter at least 10 guests`,
            'maxValue': `Maximum 5000 guests allowed`,
            'date': 'Please select a future date'
        };
        return messages[rule] || 'Invalid input';
    }

    showFieldError(field, message) {
        const formGroup = field.closest('.form-group') || field.closest('.mb-3');
        if (!formGroup) return;

        field.classList.add('is-invalid');
        field.classList.remove('is-valid');

        let feedback = formGroup.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback d-block';
            formGroup.appendChild(feedback);
        }
        feedback.textContent = message;
    }

    clearFieldError(field) {
        const formGroup = field.closest('.form-group') || field.closest('.mb-3');
        if (!formGroup) return;

        field.classList.remove('is-invalid');
        field.classList.add('is-valid');

        const feedback = formGroup.querySelector('.invalid-feedback');
        if (feedback) feedback.remove();
    }

    validateForm(formType) {
        const form = document.getElementById(formType === 'booking' ? 'bookingForm' : 'contactForm');
        if (!form) return false;

        const fields = form.querySelectorAll('[data-validate]');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    async handleSubmit(e, formType) {
        e.preventDefault();

        // Validate form
        if (!this.validateForm(formType)) {
            this.showAlert('Please fix the errors in the form', 'danger');
            return;
        }

        const form = e.target;
        const submitBtn = form.querySelector('[type="submit"]');
        const originalText = submitBtn.innerHTML;

        try {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

            // Collect form data
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            // Save to localStorage as backup
            localStorage.setItem(`${formType}_submission`, JSON.stringify({
                ...data,
                timestamp: new Date().toISOString()
            }));

            // Simulate API call - Replace with actual endpoint when backend is ready
            await this.submitToAPI(formType, data);

            // Show success message
            this.showSuccessMessage(form, formType, data);

        } catch (error) {
            console.error('Submission error:', error);
            this.showAlert('An error occurred. Your data has been saved locally. Please try again later.', 'warning');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    async submitToAPI(formType, data) {
        // Send form details via WhatsApp (opens WhatsApp or web.whatsapp with prefilled message)
        // This is the simplest client-side approach to forward form data to a WhatsApp number.
        try {
            const phoneNumber = '923039907296'; // Use international format without +

            let msg = '';
            if (formType === 'booking') {
                msg = `New booking request\n` +
                    `Name: ${data.name || ''}\n` +
                    `Phone: ${data.phone || data.contact || ''}\n` +
                    `Email: ${data.email || ''}\n` +
                    `City: ${data.city || ''}\n` +
                    `Address: ${data.address || ''}\n` +
                    `Event Type: ${data.eventType || data.event || ''}\n` +
                    `Guests: ${data.guestCount || data.guests || ''}\n` +
                    `Menu Type: ${data.menuType || ''}\n` +
                    `Event Date: ${data.eventDate || data.date || ''}\n` +
                    `Message: ${data.message || ''}`;
            } else {
                msg = `New contact form submission\n` +
                    `Name: ${data.name || ''}\n` +
                    `Phone: ${data.phone || ''}\n` +
                    `Email: ${data.email || ''}\n` +
                    `Subject: ${data.subject || ''}\n` +
                    `Message: ${data.message || ''}`;
            }

            const waUrl = 'https://wa.me/' + phoneNumber + '?text=' + encodeURIComponent(msg);

            // Open WhatsApp in a new tab/window. On mobile this will open the WhatsApp app.
            window.open(waUrl, '_blank');

            // Return a resolved promise to let the caller proceed to success UI
            return Promise.resolve({ success: true, id: Math.random().toString(36).substr(2, 9) });
        } catch (err) {
            console.error('WhatsApp send error:', err);
            throw err;
        }
    }

    showSuccessMessage(form, formType, data) {
        const referenceId = Math.random().toString(36).substr(2, 9).toUpperCase();
        const formDiv = form.parentElement;

        const successHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">
                    <i class="fas fa-check-circle me-2"></i>
                    ${formType === 'booking' ? 'Booking Request Received!' : 'Message Sent Successfully!'}
                </h4>
                <p>${formType === 'booking'
                ? 'Thank you for your booking request! We will review your details and contact you within 24 hours.'
                : 'Thank you for reaching out! We will respond to your inquiry shortly.'}</p>
                <hr>
                <p class="mb-0"><strong>Reference ID:</strong> ${referenceId}</p>
                ${formType === 'booking' ? `<p class="mb-0"><strong>Event Date:</strong> ${data.eventDate}</p>` : ''}
                <p class="mb-0"><strong>Email:</strong> ${data.email}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <div class="mt-3">
                <p>You can also reach us directly:</p>
                <ul>
                    <li><strong>WhatsApp:</strong> <a href="https://wa.me/923039907296" target="_blank">+92 303 9907296</a></li>
                    <li><strong>Email:</strong> <a href="mailto:info@altafcatering.com">info@altafcatering.com</a></li>
                    <li><strong>Phone:</strong> +92 303 9907296</li>
                </ul>
            </div>
            <button class="btn btn-primary mt-3" onclick="location.reload()">Submit Another ${formType === 'booking' ? 'Booking' : 'Message'}</button>
        `;

        formDiv.innerHTML = successHTML;

        // Scroll to success message
        formDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    showAlert(message, type = 'danger') {
        const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        const alertContainer = document.querySelector('.container .row .col-md-7') || document.body;
        const alertElement = document.createElement('div');
        alertElement.innerHTML = alertHTML;

        if (alertContainer.querySelector('.alert')) {
            alertContainer.querySelector('.alert').replaceWith(alertElement.firstChild);
        } else {
            alertContainer.insertBefore(alertElement.firstChild, alertContainer.firstChild);
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new FormValidator();
});
