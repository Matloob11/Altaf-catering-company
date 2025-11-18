/**
 * Form Handler with Email Notifications
 * Altaf Catering - Handles all form submissions
 */

// Contact Form Handler
document.addEventListener('DOMContentLoaded', function () {

    // Contact Form
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('contactSubmit');
            const originalText = submitBtn.innerHTML;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            // Get form data
            const formData = new FormData(contactForm);

            // Send AJAX request
            fetch('api/contact-handler.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('success', data.message);

                        // Reset form
                        contactForm.reset();

                        // Track event in Google Analytics
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'form_submit', {
                                'event_category': 'Contact',
                                'event_label': 'Contact Form Submission'
                            });
                        }
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'An error occurred. Please try again or contact us directly.');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });
    }

    // Booking Form
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = bookingForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            // Get form data
            const formData = new FormData(bookingForm);

            // Send AJAX request
            fetch('api/booking-handler.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Booking Response:', data);

                    if (data.success) {
                        // Show success message
                        showNotification('success', data.message);

                        // Debug: Show WhatsApp message
                        if (data.debug_message) {
                            console.log('WhatsApp Message:', data.debug_message);
                        }

                        // Debug: Show WhatsApp URL
                        if (data.whatsapp_url) {
                            console.log('WhatsApp URL:', data.whatsapp_url);
                        }

                        // Reset form
                        bookingForm.reset();

                        // Redirect to WhatsApp directly
                        if (data.whatsapp_url) {
                            console.log('Redirecting to WhatsApp...');
                            console.log('URL:', data.whatsapp_url);

                            // Direct redirect (no popup blocker issues)
                            window.location.href = data.whatsapp_url;
                        } else {
                            console.error('No WhatsApp URL received!');
                            alert('Error: WhatsApp URL not generated. Please contact admin.');
                        }
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Booking Error:', error);
                    showNotification('error', 'Error submitting booking. Please try again.');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });
    }

    // Newsletter Form
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = newsletterForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';

            // Get form data
            const formData = new FormData(newsletterForm);

            // Send AJAX request
            fetch('api/newsletter-handler.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('success', data.message);

                        // Reset form
                        newsletterForm.reset();

                        // Track event in Google Analytics
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'sign_up', {
                                'event_category': 'Newsletter',
                                'event_label': 'Newsletter Subscription'
                            });
                        }
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'An error occurred. Please try again later.');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });
    }
});

/**
 * Show notification message
 */
function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show notification-popup`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 500px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideInRight 0.5s ease;
    `;

    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2" style="font-size: 24px;"></i>
            <div class="flex-grow-1">${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Add to body
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .notification-popup {
        animation: slideInRight 0.5s ease;
    }
`;
document.head.appendChild(style);
