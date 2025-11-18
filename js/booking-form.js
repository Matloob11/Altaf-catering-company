/**
 * Enhanced Booking Form Validation & WhatsApp Submission
 * Handles form validation and WhatsApp integration for booking requests
 */

document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('bookingForm');
    if (!form) return;

    // Real-time validation feedback
    var inputs = form.querySelectorAll('input, select');
    inputs.forEach(function (input) {
        input.addEventListener('blur', function () {
            validateField(this);
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validate all fields before submission
        if (!validateForm()) {
            alert('Please fill all required fields correctly!');
            return;
        }

        // Get form values
        var name = document.getElementById('bookingName').value.trim();
        var phone = document.getElementById('bookingPhone').value.trim();
        var email = document.getElementById('bookingEmail').value.trim();
        var city = document.getElementById('bookingCity').value.trim();
        var address = document.getElementById('bookingAddress').value.trim();
        var eventType = document.getElementById('bookingEventType').value;
        var guests = document.getElementById('bookingGuests').value;
        var menuType = document.getElementById('bookingMenuType').value;
        var date = document.getElementById('bookingDate').value;

        // Validate phone format (Pakistani number)
        if (!validatePhone(phone)) {
            alert('Please enter a valid phone number (e.g., 03009907296)');
            return;
        }

        // Validate email format
        if (!validateEmail(email)) {
            alert('Please enter a valid email address');
            return;
        }

        // Validate date is in future
        var eventDate = new Date(date);
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        if (eventDate < today) {
            alert('Event date must be in the future');
            return;
        }

        // Build WhatsApp message
        var phoneNumber = '923039907296'; // without +
        var msg = 'New Booking Request from Website\n\n'
            + '👤 Name: ' + name + '\n'
            + '📱 Phone: ' + phone + '\n'
            + '📧 Email: ' + email + '\n'
            + '🏙️ City: ' + city + '\n'
            + '📍 Address: ' + address + '\n'
            + '🎉 Event Type: ' + eventType + '\n'
            + '👥 Number of Guests: ' + guests + '\n'
            + '🍽️ Menu Type: ' + menuType + '\n'
            + '📅 Event Date: ' + date + '\n\n'
            + 'Please confirm availability and provide quotation.';

        var waUrl = 'https://wa.me/' + phoneNumber + '?text=' + encodeURIComponent(msg);
        window.open(waUrl, '_blank');

        // Reset form after successful submission
        form.reset();
        alert('Booking request sent! Check WhatsApp for confirmation.');
    });

    // Validation functions
    function validateField(field) {
        var value = field.value.trim();
        var isValid = true;

        if (field.hasAttribute('required') && !value) {
            isValid = false;
        }

        if (field.type === 'email' && value && !validateEmail(value)) {
            isValid = false;
        }

        if (field.name === 'phone' && value && !validatePhone(value)) {
            isValid = false;
        }

        if (field.name === 'guests' && value && value < 1) {
            isValid = false;
        }

        // Visual feedback
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }

        return isValid;
    }

    function validateForm() {
        var allValid = true;
        inputs.forEach(function (input) {
            if (!validateField(input)) {
                allValid = false;
            }
        });
        return allValid;
    }

    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validatePhone(phone) {
        // Pakistani phone format: 03XX XXXXXXX or +923XX XXXXXXX
        var phoneRegex = /^(\+92|0)?3\d{2}\d{7}$/;
        var cleanPhone = phone.replace(/\s|-/g, '');
        return phoneRegex.test(cleanPhone);
    }
});
