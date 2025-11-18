/**
 * Admin Panel Enhancements
 * Modern features and interactions
 */

(function () {
    'use strict';

    // ========== SCROLL TO TOP BUTTON ==========
    function initScrollToTop() {
        // Create scroll to top button
        const scrollBtn = document.createElement('button');
        scrollBtn.className = 'scroll-top';
        scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        scrollBtn.setAttribute('aria-label', 'Scroll to top');
        document.body.appendChild(scrollBtn);

        // Show/hide on scroll
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });

        // Scroll to top on click
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ========== SMOOTH SCROLL FOR ANCHOR LINKS ==========
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '#!') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    }

    // ========== AUTO-HIDE ALERTS ==========
    function initAutoHideAlerts() {
        document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    }

    // ========== FORM VALIDATION ENHANCEMENT ==========
    function initFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }

    // ========== TOOLTIPS INITIALIZATION ==========
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // ========== POPOVERS INITIALIZATION ==========
    function initPopovers() {
        const popoverTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="popover"]')
        );
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // ========== CARD COLLAPSE ANIMATION ==========
    function initCardCollapse() {
        document.querySelectorAll('[data-card-collapse]').forEach(btn => {
            btn.addEventListener('click', function () {
                const card = this.closest('.card');
                const body = card.querySelector('.card-body');
                const icon = this.querySelector('i');

                if (body.style.display === 'none') {
                    body.style.display = 'block';
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                } else {
                    body.style.display = 'none';
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                }
            });
        });
    }

    // ========== COPY TO CLIPBOARD ==========
    function initCopyToClipboard() {
        document.querySelectorAll('[data-copy]').forEach(btn => {
            btn.addEventListener('click', function () {
                const text = this.getAttribute('data-copy');
                navigator.clipboard.writeText(text).then(() => {
                    // Show success feedback
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    this.classList.add('btn-success');

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                    }, 2000);
                });
            });
        });
    }

    // ========== CONFIRM DELETE ==========
    function initConfirmDelete() {
        document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                const message = this.getAttribute('data-confirm-delete') ||
                    'Are you sure you want to delete this item?';
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    // ========== LOADING STATE FOR BUTTONS ==========
    function initLoadingButtons() {
        document.querySelectorAll('[data-loading-text]').forEach(btn => {
            btn.addEventListener('click', function () {
                const loadingText = this.getAttribute('data-loading-text');
                const originalText = this.innerHTML;

                this.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${loadingText}`;
                this.disabled = true;

                // Re-enable after form submission or timeout
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            });
        });
    }

    // ========== SEARCH HIGHLIGHT ==========
    function initSearchHighlight() {
        const searchInputs = document.querySelectorAll('[data-search-target]');
        searchInputs.forEach(input => {
            input.addEventListener('input', function () {
                const target = document.querySelector(this.getAttribute('data-search-target'));
                const searchText = this.value.toLowerCase();

                if (target) {
                    const items = target.querySelectorAll('[data-searchable]');
                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(searchText) || searchText === '') {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }
            });
        });
    }

    // ========== COUNTER ANIMATION ==========
    function animateCounter(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.textContent = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    function initCounters() {
        const counters = document.querySelectorAll('[data-counter]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    const target = parseInt(entry.target.getAttribute('data-counter'));
                    animateCounter(entry.target, 0, target, 2000);
                    entry.target.classList.add('counted');
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    }

    // ========== LAZY LOAD IMAGES ==========
    function initLazyLoad() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.getAttribute('data-src');
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // ========== SIDEBAR TOGGLE FOR MOBILE ==========
    function initSidebarToggle() {
        const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
        const sidebar = document.querySelector('.sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });
        }
    }

    // ========== NOTIFICATION SYSTEM ==========
    window.showNotification = function (message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    };

    // ========== INITIALIZE ALL ==========
    function init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }

        // Initialize all features
        initScrollToTop();
        initSmoothScroll();
        initAutoHideAlerts();
        initFormValidation();
        initTooltips();
        initPopovers();
        initCardCollapse();
        initCopyToClipboard();
        initConfirmDelete();
        initLoadingButtons();
        initSearchHighlight();
        initCounters();
        initLazyLoad();
        initSidebarToggle();

        console.log('✨ Admin enhancements loaded successfully!');
    }

    // Start initialization
    init();

})();
