/**
 * Altaf Catering Admin Panel - Common JavaScript
 * Handles responsive menu, animations, and common functionality
 */

(function () {
    'use strict';

    // ========== MOBILE MENU TOGGLE ==========
    function initMobileMenu() {
        // Check if layout manager is available (preferred method)
        if (window.AdminLayoutManager || window.adminLayoutManager) {
            console.log('Layout Manager detected - using advanced layout system');
            return; // Layout manager will handle this
        }

        // Fallback: Create basic mobile menu toggle if layout manager is not available
        if (!document.querySelector('.mobile-menu-toggle')) {
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'mobile-menu-toggle';
            toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
            toggleBtn.setAttribute('aria-label', 'Toggle Menu');
            document.body.appendChild(toggleBtn);

            // Create overlay
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);

            // Toggle sidebar
            toggleBtn.addEventListener('click', function () {
                const sidebar = document.querySelector('.sidebar');
                const icon = this.querySelector('i');

                if (sidebar) {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');

                    // Change icon
                    if (sidebar.classList.contains('show')) {
                        icon.className = 'fas fa-times';
                    } else {
                        icon.className = 'fas fa-bars';
                    }
                }
            });

            // Close sidebar when clicking overlay
            overlay.addEventListener('click', function () {
                const sidebar = document.querySelector('.sidebar');
                const icon = toggleBtn.querySelector('i');

                if (sidebar) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    icon.className = 'fas fa-bars';
                }
            });

            // Close sidebar when clicking a link (mobile only)
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 991) {
                        const sidebar = document.querySelector('.sidebar');
                        const icon = toggleBtn.querySelector('i');

                        if (sidebar) {
                            sidebar.classList.remove('show');
                            overlay.classList.remove('show');
                            icon.className = 'fas fa-bars';
                        }
                    }
                });
            });
        }
    }

    // ========== SMOOTH SCROLL ==========
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

    // ========== AUTO DISMISS ALERTS ==========
    function initAlertAutoDismiss() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000); // 5 seconds
        });
    }

    // ========== FORM VALIDATION FEEDBACK ==========
    function initFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }

    // ========== TOOLTIP INITIALIZATION ==========
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // ========== POPOVER INITIALIZATION ==========
    function initPopovers() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // ========== CONFIRM DELETE ==========
    function initConfirmDelete() {
        const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                const message = this.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this item?';
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    // ========== TABLE SEARCH ==========
    function initTableSearch() {
        const searchInputs = document.querySelectorAll('[data-table-search]');
        searchInputs.forEach(input => {
            const tableId = input.getAttribute('data-table-search');
            const table = document.getElementById(tableId);

            if (table) {
                input.addEventListener('keyup', function () {
                    const searchTerm = this.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    }

    // ========== CARD COLLAPSE ==========
    function initCardCollapse() {
        const collapseButtons = document.querySelectorAll('[data-card-collapse]');
        collapseButtons.forEach(button => {
            button.addEventListener('click', function () {
                const card = this.closest('.card');
                const cardBody = card.querySelector('.card-body');
                const icon = this.querySelector('i');

                if (cardBody) {
                    cardBody.classList.toggle('d-none');
                    if (icon) {
                        icon.classList.toggle('fa-chevron-up');
                        icon.classList.toggle('fa-chevron-down');
                    }
                }
            });
        });
    }

    // ========== COPY TO CLIPBOARD ==========
    function initCopyToClipboard() {
        const copyButtons = document.querySelectorAll('[data-copy]');
        copyButtons.forEach(button => {
            button.addEventListener('click', function () {
                const text = this.getAttribute('data-copy');
                navigator.clipboard.writeText(text).then(() => {
                    // Show success feedback
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                });
            });
        });
    }

    // ========== RESPONSIVE TABLE ==========
    function makeTablesResponsive() {
        const tables = document.querySelectorAll('table:not(.table-responsive table)');
        tables.forEach(table => {
            // Add data-label attributes for mobile view
            const headers = table.querySelectorAll('thead th');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index].textContent);
                    }
                });
            });
        });
    }

    // ========== BACK TO TOP BUTTON ==========
    function initBackToTop() {
        const backToTop = document.createElement('button');
        backToTop.className = 'btn btn-primary position-fixed bottom-0 end-0 m-4 rounded-circle d-none';
        backToTop.style.width = '50px';
        backToTop.style.height = '50px';
        backToTop.style.zIndex = '1050';
        backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
        backToTop.setAttribute('aria-label', 'Back to Top');

        document.body.appendChild(backToTop);

        // Show/hide based on scroll
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) {
                backToTop.classList.remove('d-none');
            } else {
                backToTop.classList.add('d-none');
            }
        });

        // Scroll to top on click
        backToTop.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ========== INITIALIZE ALL ==========
    function init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                initMobileMenu();
                initSmoothScroll();
                initAlertAutoDismiss();
                initFormValidation();
                initTooltips();
                initPopovers();
                initConfirmDelete();
                initTableSearch();
                initCardCollapse();
                initCopyToClipboard();
                makeTablesResponsive();
                initBackToTop();
            });
        } else {
            initMobileMenu();
            initSmoothScroll();
            initAlertAutoDismiss();
            initFormValidation();
            initTooltips();
            initPopovers();
            initConfirmDelete();
            initTableSearch();
            initCardCollapse();
            initCopyToClipboard();
            makeTablesResponsive();
            initBackToTop();
        }
    }

    // Start initialization
    init();

    // Re-initialize on window resize (for responsive changes)
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            makeTablesResponsive();
        }, 250);
    });

})();
