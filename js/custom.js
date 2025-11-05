// Cookie Consent
function checkCookieConsent() {
    if (!localStorage.getItem('cookieConsent')) {
        document.getElementById('cookieConsent').style.display = 'block';
    }
}

function acceptCookies() {
    localStorage.setItem('cookieConsent', 'accepted');
    document.getElementById('cookieConsent').style.display = 'none';
}

// Form Validation with better feedback
if (document.getElementById('bookingForm')) {
    document.getElementById('bookingForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Show loading state
        const submitBtn = document.getElementById('bookingSubmit');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
        submitBtn.disabled = true;

        // Simulate form submission (replace with actual API call)
        setTimeout(() => {
            // Show success message
            const formDiv = document.querySelector('.form');
            formDiv.innerHTML = `
                <div class="alert alert-success">
                    <h4 class="alert-heading">Thank you for your booking!</h4>
                    <p>We have received your request and will contact you shortly.</p>
                    <hr>
                    <p class="mb-0">Reference #: ${Math.random().toString(36).substr(2, 9)}</p>
                </div>
                <button onclick="window.location.reload()" class="btn btn-primary mt-3">Make Another Booking</button>
            `;
        }, 1500);
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    checkCookieConsent();
});

// Add smooth scrolling for all hash links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetElement = document.querySelector(this.getAttribute('href'));
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});