/**
 * Lazy Loading Implementation
 * Altaf Catering - Performance Optimization
 */

(function () {
    'use strict';

    // Lazy load images
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');

        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;

                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                    }

                    img.classList.add('loaded');
                    img.removeAttribute('data-src');
                    img.removeAttribute('data-srcset');

                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Lazy load background images
    function lazyLoadBackgrounds() {
        const elements = document.querySelectorAll('[data-bg]');

        const bgObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    element.style.backgroundImage = `url(${element.dataset.bg})`;
                    element.classList.add('loaded');
                    element.removeAttribute('data-bg');
                    observer.unobserve(element);
                }
            });
        }, {
            rootMargin: '50px 0px'
        });

        elements.forEach(el => bgObserver.observe(el));
    }

    // Lazy load iframes (videos, maps)
    function lazyLoadIframes() {
        const iframes = document.querySelectorAll('iframe[data-src]');

        const iframeObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const iframe = entry.target;
                    iframe.src = iframe.dataset.src;
                    iframe.removeAttribute('data-src');
                    observer.unobserve(iframe);
                }
            });
        }, {
            rootMargin: '200px 0px'
        });

        iframes.forEach(iframe => iframeObserver.observe(iframe));
    }

    // Preload critical images
    function preloadCriticalImages() {
        const criticalImages = document.querySelectorAll('img[data-critical]');

        criticalImages.forEach(img => {
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        // Check for IntersectionObserver support
        if ('IntersectionObserver' in window) {
            preloadCriticalImages();
            lazyLoadImages();
            lazyLoadBackgrounds();
            lazyLoadIframes();

        } else {
            // Fallback for older browsers
            const images = document.querySelectorAll('img[data-src]');
            images.forEach(img => {
                img.src = img.dataset.src;
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                }
            });
        }
    }

    // Expose to global scope if needed
    window.lazyLoad = {
        init: init,
        images: lazyLoadImages,
        backgrounds: lazyLoadBackgrounds,
        iframes: lazyLoadIframes
    };
})();

// Performance monitoring
if ('PerformanceObserver' in window) {
    // Monitor Largest Contentful Paint
    const lcpObserver = new PerformanceObserver((list) => {
        const entries = list.getEntries();
        const lastEntry = entries[entries.length - 1];
        console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
    });

    try {
        lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
    } catch (e) {
        // Ignore if not supported
    }
}
