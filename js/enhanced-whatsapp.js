/**
 * Enhanced WhatsApp Integration
 * Provides better user interaction and analytics
 */

(function () {
    'use strict';

    // Configuration
    const config = {
        phoneNumber: '923039907296',
        businessName: 'Altaf Catering',
        defaultMessage: 'Hello Altaf Catering! I would like to inquire about your services.',
        showDelay: 3000, // Show quick menu after 3 seconds
        hideDelay: 500   // Hide after 500ms
    };

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        initEnhancedWhatsApp();
    });

    function initEnhancedWhatsApp() {
        const whatsappContainer = document.querySelector('.whatsapp-container');
        const quickMenu = document.querySelector('.whatsapp-quick-menu');
        const mainBtn = document.querySelector('#mainWhatsAppBtn');

        if (!whatsappContainer || !quickMenu || !mainBtn) return;

        // Add click tracking
        addClickTracking();

        // Add keyboard navigation
        addKeyboardNavigation();

        // Add auto-show functionality
        addAutoShow();

        // Add mobile touch handling
        addMobileHandling();
    }

    function addClickTracking() {
        // Track main WhatsApp button clicks
        const mainBtn = document.querySelector('#mainWhatsAppBtn');
        if (mainBtn) {
            mainBtn.addEventListener('click', function () {
                trackEvent('WhatsApp', 'Main Button Click', 'Contact');
            });
        }

        // Track quick action clicks
        const quickActions = document.querySelectorAll('.quick-action');
        quickActions.forEach(function (action, index) {
            action.addEventListener('click', function () {
                const actionText = action.querySelector('span').textContent;
                trackEvent('WhatsApp', 'Quick Action', actionText);
            });
        });
    }

    function addKeyboardNavigation() {
        const quickActions = document.querySelectorAll('.quick-action');

        quickActions.forEach(function (action, index) {
            action.setAttribute('tabindex', '0');

            action.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    action.click();
                }

                // Arrow key navigation
                if (e.key === 'ArrowDown' && index < quickActions.length - 1) {
                    e.preventDefault();
                    quickActions[index + 1].focus();
                }

                if (e.key === 'ArrowUp' && index > 0) {
                    e.preventDefault();
                    quickActions[index - 1].focus();
                }
            });
        });
    }

    function addAutoShow() {
        let showTimer;
        let hideTimer;
        const container = document.querySelector('.whatsapp-container');
        const quickMenu = document.querySelector('.whatsapp-quick-menu');

        if (!container || !quickMenu) return;

        // Show menu on hover with delay
        container.addEventListener('mouseenter', function () {
            clearTimeout(hideTimer);
            showTimer = setTimeout(function () {
                quickMenu.classList.add('show-auto');
            }, 300);
        });

        // Hide menu on leave
        container.addEventListener('mouseleave', function () {
            clearTimeout(showTimer);
            hideTimer = setTimeout(function () {
                quickMenu.classList.remove('show-auto');
            }, config.hideDelay);
        });
    }

    function addMobileHandling() {
        if (!isMobileDevice()) return;

        const container = document.querySelector('.whatsapp-container');
        const quickMenu = document.querySelector('.whatsapp-quick-menu');

        if (!container || !quickMenu) return;

        let touchStartTime;

        // Long press to show menu on mobile
        container.addEventListener('touchstart', function (e) {
            touchStartTime = Date.now();
        });

        container.addEventListener('touchend', function (e) {
            const touchDuration = Date.now() - touchStartTime;

            // If touch was longer than 500ms, show menu
            if (touchDuration > 500) {
                e.preventDefault();
                quickMenu.classList.toggle('show-mobile');
            }
        });

        // Close menu when clicking outside
        document.addEventListener('touchstart', function (e) {
            if (!container.contains(e.target)) {
                quickMenu.classList.remove('show-mobile');
            }
        });
    }

    function trackEvent(category, action, label) {
        // Google Analytics tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                event_category: category,
                event_label: label
            });
        }

        // Facebook Pixel tracking
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Contact', {
                content_name: label
            });
        }

        // Console log for debugging
        console.log('Event tracked:', category, action, label);
    }

    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Add CSS for auto-show and mobile states
    const style = document.createElement('style');
    style.textContent = `
        .whatsapp-quick-menu.show-auto,
        .whatsapp-quick-menu.show-mobile {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) scale(1) !important;
        }
        
        @media (max-width: 768px) {
            .whatsapp-quick-menu.show-mobile {
                position: fixed;
                top: 50%;
                left: 50%;
                right: auto;
                bottom: auto;
                transform: translate(-50%, -50%) !important;
                width: 90vw;
                max-width: 320px;
                z-index: 99999;
            }
            
            .whatsapp-quick-menu.show-mobile::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: -1;
            }
        }
    `;
    document.head.appendChild(style);

})();