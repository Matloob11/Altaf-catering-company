/**
 * WOW.js Initialization - COMPLETE ANIMATION SYSTEM
 * Handles scroll-triggered animations with staggered delays
 */

(function () {
    'use strict';

    function initWOW() {
        console.log('🎬 Initializing WOW.js animations...');

        // Check if WOW is loaded
        if (typeof WOW === 'undefined') {
            console.error('❌ WOW.js not loaded! Make sure lib/wow/wow.min.js is included.');
            return;
        }

        // Check if animate.css is loaded
        if (!document.querySelector('link[href*="animate"]')) {
            console.warn('⚠️ animate.css may not be loaded');
        }

        try {
            // Initialize WOW with optimal settings
            var wow = new WOW({
                boxClass: 'wow',                    // CSS class to trigger animations
                animateClass: 'animated',           // Class from animate.css
                offset: 100,                        // Trigger when 100px visible in viewport
                mobile: true,                       // Animations on mobile
                live: true,                         // Watch for new elements
                resetAnimation: true,               // Allow re-triggering animations
                scrollContainer: null,              // Use window scroll
                callback: function (box) {
                    var animation = box.getAttribute('data-wow-animation') ||
                        box.className.match(/fadeIn|bounceIn|zoomIn|slideIn|rotateIn|flipIn|pulse|heartBeat/);
                    console.log('✨ Animation:', animation, 'triggered for element');
                }
            });

            wow.init();

            // Count WOW elements
            var wowElements = document.querySelectorAll('.wow');
            console.log('✅ WOW.js initialized! Found ' + wowElements.length + ' animated elements');

            if (wowElements.length === 0) {
                console.warn('⚠️ No elements with .wow class found!');
            }

            // Reinitialize on dynamic content
            setInterval(function () {
                wow.sync();
            }, 5000);

        } catch (error) {
            console.error('❌ WOW.js initialization error:', error);
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(initWOW, 500);
        });
    } else {
        setTimeout(initWOW, 500);
    }

    // Also try on window load
    window.addEventListener('load', function () {
        setTimeout(function () {
            if (typeof WOW !== 'undefined') {
                try {
                    var wow = new WOW({
                        boxClass: 'wow',
                        animateClass: 'animated',
                        offset: 100,
                        mobile: true,
                        live: true,
                        resetAnimation: true
                    });
                    wow.init();
                    wow.sync();
                    console.log('✅ WOW.js reinitialized on window load');
                } catch (e) {
                    console.log('WOW sync already done');
                }
            }
        }, 500);
    });

})();

