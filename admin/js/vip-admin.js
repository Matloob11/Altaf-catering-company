/**
 * VIP Admin Panel JavaScript
 * Advanced UI/UX Interactions and Animations
 */

(function () {
    'use strict';

    // VIP Loader Management
    class VIPLoader {
        constructor() {
            this.spinner = document.getElementById('adminSpinner');
            this.init();
        }

        init() {
            // Show loader on page load
            this.show();

            // Hide loader when page is fully loaded
            window.addEventListener('load', () => {
                setTimeout(() => {
                    this.hide();
                }, 800); // Minimum display time for VIP effect
            });

            // Show loader on navigation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                if (link && !link.hasAttribute('target') && !link.href.includes('#')) {
                    this.show();
                }
            });
        }

        show() {
            if (this.spinner) {
                this.spinner.classList.add('show');
            }
        }

        hide() {
            if (this.spinner) {
                this.spinner.classList.remove('show');
            }
        }
    }

    // VIP Sidebar Management
    class VIPSidebar {
        constructor() {
            this.sidebar = document.querySelector('.sidebar');
            this.toggleBtn = document.querySelector('[data-bs-toggle="collapse"][data-bs-target="#sidebarMenu"]');
            this.init();
        }

        init() {
            // Auto-scroll active item to view
            this.scrollActiveToView();

            // Add hover effects
            this.addHoverEffects();

            // Handle mobile toggle
            if (this.toggleBtn) {
                this.toggleBtn.addEventListener('click', () => {
                    this.toggleMobile();
                });
            }

            // Close sidebar on outside click (mobile)
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    if (!this.sidebar.contains(e.target) && !this.toggleBtn?.contains(e.target)) {
                        this.closeMobile();
                    }
                }
            });
        }

        scrollActiveToView() {
            const activeLink = this.sidebar?.querySelector('.nav-link.active');
            if (activeLink) {
                setTimeout(() => {
                    activeLink.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Add highlight effect
                    activeLink.style.boxShadow = '0 0 20px rgba(102, 126, 234, 0.6)';
                    setTimeout(() => {
                        activeLink.style.boxShadow = '';
                    }, 2000);
                }, 300);
            }
        }

        addHoverEffects() {
            const navLinks = this.sidebar?.querySelectorAll('.nav-link');
            navLinks?.forEach(link => {
                link.addEventListener('mouseenter', () => {
                    this.createRippleEffect(link);
                });
            });
        }

        createRippleEffect(element) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;

            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = '50%';
            ripple.style.top = '50%';
            ripple.style.transform = 'translate(-50%, -50%) scale(0)';

            element.style.position = 'relative';
            element.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        }

        toggleMobile() {
            this.sidebar?.classList.toggle('show');
        }

        closeMobile() {
            this.sidebar?.classList.remove('show');
        }

        // Enhanced mobile handling
        handleMobileInteractions() {
            // Close sidebar on navigation
            const navLinks = this.sidebar?.querySelectorAll('.nav-link');
            navLinks?.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        setTimeout(() => this.closeMobile(), 300);
                    }
                });
            });
        }
    }

    // VIP Card Animations
    class VIPCardAnimations {
        constructor() {
            this.init();
        }

        init() {
            this.observeCards();
            this.addStatCounters();
            this.addHoverEffects();
        }

        observeCards() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = Math.random() * 0.3 + 's';
                        entry.target.classList.add('animate-in');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.card').forEach(card => {
                observer.observe(card);
            });
        }

        addStatCounters() {
            const statNumbers = document.querySelectorAll('[data-count]');
            statNumbers.forEach(element => {
                const target = parseInt(element.getAttribute('data-count'));
                this.animateCounter(element, target);
            });
        }

        animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 30);
        }

        addHoverEffects() {
            document.querySelectorAll('.card-hover').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    this.createParticleEffect(card);
                });
            });
        }

        createParticleEffect(element) {
            for (let i = 0; i < 5; i++) {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    background: linear-gradient(45deg, #667eea, #764ba2);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                `;

                const rect = element.getBoundingClientRect();
                particle.style.left = rect.left + Math.random() * rect.width + 'px';
                particle.style.top = rect.top + Math.random() * rect.height + 'px';

                document.body.appendChild(particle);

                particle.animate([
                    { transform: 'translateY(0px) scale(1)', opacity: 1 },
                    { transform: 'translateY(-50px) scale(0)', opacity: 0 }
                ], {
                    duration: 1000,
                    easing: 'ease-out'
                }).onfinish = () => particle.remove();
            }
        }
    }

    // VIP Button Effects
    class VIPButtonEffects {
        constructor() {
            this.init();
        }

        init() {
            this.addClickEffects();
            this.addHoverSounds();
        }

        addClickEffects() {
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    this.createClickWave(e);
                });
            });
        }

        createClickWave(e) {
            const button = e.currentTarget;
            const rect = button.getBoundingClientRect();
            const wave = document.createElement('span');

            wave.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.5);
                transform: scale(0);
                animation: wave 0.6s linear;
                pointer-events: none;
            `;

            const size = Math.max(rect.width, rect.height);
            wave.style.width = wave.style.height = size + 'px';
            wave.style.left = (e.clientX - rect.left - size / 2) + 'px';
            wave.style.top = (e.clientY - rect.top - size / 2) + 'px';

            button.style.position = 'relative';
            button.appendChild(wave);

            setTimeout(() => wave.remove(), 600);
        }

        addHoverSounds() {
            // Add subtle audio feedback (optional)
            document.querySelectorAll('.btn-vip').forEach(button => {
                button.addEventListener('mouseenter', () => {
                    // Create subtle hover sound effect
                    this.playHoverSound();
                });
            });
        }

        playHoverSound() {
            // Create audio context for subtle UI sounds
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            } catch (e) {
                // Silently fail if audio context is not supported
            }
        }
    }

    // VIP Table Enhancements
    class VIPTableEnhancements {
        constructor() {
            this.init();
        }

        init() {
            this.addRowAnimations();
            this.addSortingEffects();
        }

        addRowAnimations() {
            document.querySelectorAll('.table tbody tr').forEach((row, index) => {
                row.style.animationDelay = (index * 0.05) + 's';
                row.classList.add('fade-in-row');
            });
        }

        addSortingEffects() {
            document.querySelectorAll('.table th').forEach(header => {
                header.addEventListener('click', () => {
                    this.createSortingEffect(header);
                });
            });
        }

        createSortingEffect(header) {
            const arrow = document.createElement('i');
            arrow.className = 'fas fa-sort-up ms-2';
            arrow.style.animation = 'bounce 0.5s ease-in-out';

            // Remove existing arrows
            header.querySelectorAll('.fas').forEach(icon => {
                if (icon.classList.contains('fa-sort-up') || icon.classList.contains('fa-sort-down')) {
                    icon.remove();
                }
            });

            header.appendChild(arrow);
        }
    }

    // VIP Form Enhancements
    class VIPFormEnhancements {
        constructor() {
            this.init();
        }

        init() {
            this.addFloatingLabels();
            this.addValidationEffects();
            this.addProgressiveEnhancement();
        }

        addFloatingLabels() {
            document.querySelectorAll('.form-control, .form-select').forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', () => {
                    if (!input.value) {
                        input.parentElement.classList.remove('focused');
                    }
                });
            });
        }

        addValidationEffects() {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    this.validateForm(form, e);
                });
            });
        }

        validateForm(form, e) {
            const inputs = form.querySelectorAll('.form-control[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    this.showFieldError(input);
                } else {
                    this.showFieldSuccess(input);
                }
            });

            if (!isValid) {
                e.preventDefault();
                this.showFormError(form);
            }
        }

        showFieldError(input) {
            input.style.borderColor = '#ef4444';
            input.style.boxShadow = '0 0 0 0.2rem rgba(239, 68, 68, 0.25)';

            setTimeout(() => {
                input.style.borderColor = '';
                input.style.boxShadow = '';
            }, 3000);
        }

        showFieldSuccess(input) {
            input.style.borderColor = '#10b981';
            input.style.boxShadow = '0 0 0 0.2rem rgba(16, 185, 129, 0.25)';

            setTimeout(() => {
                input.style.borderColor = '';
                input.style.boxShadow = '';
            }, 2000);
        }

        showFormError(form) {
            form.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                form.style.animation = '';
            }, 500);
        }

        addProgressiveEnhancement() {
            // Add auto-save functionality
            document.querySelectorAll('.form-control').forEach(input => {
                let timeout;
                input.addEventListener('input', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        this.autoSave(input);
                    }, 2000);
                });
            });
        }

        autoSave(input) {
            // Create subtle save indicator
            const indicator = document.createElement('span');
            indicator.textContent = '✓ Saved';
            indicator.style.cssText = `
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                color: #10b981;
                font-size: 0.8rem;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;

            input.parentElement.style.position = 'relative';
            input.parentElement.appendChild(indicator);

            setTimeout(() => {
                indicator.style.opacity = '1';
                setTimeout(() => {
                    indicator.style.opacity = '0';
                    setTimeout(() => indicator.remove(), 300);
                }, 1500);
            }, 100);
        }
    }

    // VIP Notification System
    class VIPNotifications {
        constructor() {
            this.container = this.createContainer();
            this.init();
        }

        createContainer() {
            const container = document.createElement('div');
            container.id = 'vip-notifications';
            container.style.cssText = `
                position: fixed;
                top: 90px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(container);
            return container;
        }

        init() {
            // Listen for custom notification events
            document.addEventListener('vip-notify', (e) => {
                this.show(e.detail);
            });
        }

        show(options) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${options.type || 'info'} alert-dismissible fade show`;
            notification.style.cssText = `
                margin-bottom: 10px;
                animation: slideInRight 0.3s ease;
            `;

            notification.innerHTML = `
                <i class="fas fa-${this.getIcon(options.type)} me-2"></i>
                ${options.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            this.container.appendChild(notification);

            // Auto-remove after delay
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, options.duration || 5000);
        }

        getIcon(type) {
            const icons = {
                success: 'check-circle',
                danger: 'exclamation-triangle',
                warning: 'exclamation-circle',
                info: 'info-circle'
            };
            return icons[type] || 'info-circle';
        }
    }

    // Initialize VIP Components
    document.addEventListener('DOMContentLoaded', () => {
        new VIPLoader();
        new VIPSidebar();
        new VIPCardAnimations();
        new VIPButtonEffects();
        new VIPTableEnhancements();
        new VIPFormEnhancements();
        new VIPNotifications();

        // Add custom CSS animations
        addCustomAnimations();

        // Initialize tooltips and popovers
        initializeBootstrapComponents();

        console.log('🎉 VIP Admin Panel Loaded Successfully!');
    });

    // Add custom CSS animations
    function addCustomAnimations() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes wave {
                0% { transform: scale(0); opacity: 1; }
                100% { transform: scale(4); opacity: 0; }
            }
            
            @keyframes ripple {
                0% { transform: translate(-50%, -50%) scale(0); }
                100% { transform: translate(-50%, -50%) scale(4); opacity: 0; }
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
            
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            
            .animate-in {
                animation: fadeInUp 0.6s ease forwards;
            }
            
            .fade-in-row {
                animation: fadeInUp 0.4s ease forwards;
            }
        `;
        document.head.appendChild(style);
    }

    // Initialize Bootstrap components
    function initializeBootstrapComponents() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // Global VIP utility functions
    window.VIP = {
        notify: (message, type = 'info', duration = 5000) => {
            document.dispatchEvent(new CustomEvent('vip-notify', {
                detail: { message, type, duration }
            }));
        },

        showLoader: () => {
            document.getElementById('adminSpinner')?.classList.add('show');
        },

        hideLoader: () => {
            document.getElementById('adminSpinner')?.classList.remove('show');
        }
    };

})();