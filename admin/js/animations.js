// 🎨 Advanced Animation Library for Admin Panel

class AnimationController {
    constructor() {
        this.init();
    }

    init() {
        this.setupPageTransitions();
        this.setupScrollAnimations();
        this.setupHoverEffects();
        this.setupCounterAnimations();
        this.setupParticles();
    }

    // Page load animations
    setupPageTransitions() {
        // Fade in main content
        const main = document.querySelector('main');
        if (main) {
            main.style.opacity = '0';
            main.style.transform = 'translateY(20px)';
            setTimeout(() => {
                main.style.transition = 'all 0.6s ease';
                main.style.opacity = '1';
                main.style.transform = 'translateY(0)';
            }, 100);
        }

        // Stagger animation for cards
        this.staggerAnimation('.card', 100);
        this.staggerAnimation('.stat-card', 150);
    }

    // Stagger animation helper
    staggerAnimation(selector, delay) {
        document.querySelectorAll(selector).forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px) scale(0.95)';
            setTimeout(() => {
                element.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0) scale(1)';
            }, index * delay);
        });
    }

    // Scroll-triggered animations
    setupScrollAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.card, .widget, .table').forEach(el => {
            observer.observe(el);
        });
    }

    // Enhanced hover effects
    setupHoverEffects() {
        // Card tilt effect
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            });
        });

        // Button ripple effect
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function (e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.6);
                    transform: scale(0);
                    animation: ripple-animation 0.6s ease-out;
                    pointer-events: none;
                `;

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        });
    }

    // Counter animations
    setupCounterAnimations() {
        const animateValue = (element, start, end, duration) => {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.textContent = value;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        };

        // Animate stat numbers
        setTimeout(() => {
            document.querySelectorAll('.h5, .stat-value').forEach(el => {
                const text = el.textContent.trim();
                const number = parseInt(text.replace(/[^0-9]/g, ''));
                if (!isNaN(number) && number > 0) {
                    animateValue(el, 0, number, 1500);
                }
            });
        }, 500);
    }

    // Particle background effect
    setupParticles() {
        const createParticle = () => {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.cssText = `
                position: fixed;
                width: 4px;
                height: 4px;
                background: rgba(102, 126, 234, 0.3);
                border-radius: 50%;
                pointer-events: none;
                z-index: 1;
                left: ${Math.random() * 100}vw;
                top: ${Math.random() * 100}vh;
                animation: float-particle ${5 + Math.random() * 10}s linear infinite;
            `;
            document.body.appendChild(particle);

            setTimeout(() => particle.remove(), 15000);
        };

        // Create particles periodically
        setInterval(createParticle, 2000);
    }

    // Smooth scroll
    smoothScroll(target) {
        const element = document.querySelector(target);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    // Shake animation
    shake(element) {
        element.style.animation = 'shake 0.5s';
        setTimeout(() => {
            element.style.animation = '';
        }, 500);
    }

    // Bounce animation
    bounce(element) {
        element.style.animation = 'bounce 0.5s';
        setTimeout(() => {
            element.style.animation = '';
        }, 500);
    }

    // Fade in element
    fadeIn(element, duration = 500) {
        element.style.opacity = '0';
        element.style.transition = `opacity ${duration}ms`;
        setTimeout(() => {
            element.style.opacity = '1';
        }, 10);
    }

    // Fade out element
    fadeOut(element, duration = 500) {
        element.style.transition = `opacity ${duration}ms`;
        element.style.opacity = '0';
        setTimeout(() => {
            element.style.display = 'none';
        }, duration);
    }

    // Slide in from left
    slideInLeft(element, duration = 500) {
        element.style.transform = 'translateX(-100%)';
        element.style.transition = `transform ${duration}ms ease`;
        setTimeout(() => {
            element.style.transform = 'translateX(0)';
        }, 10);
    }

    // Slide in from right
    slideInRight(element, duration = 500) {
        element.style.transform = 'translateX(100%)';
        element.style.transition = `transform ${duration}ms ease`;
        setTimeout(() => {
            element.style.transform = 'translateX(0)';
        }, 10);
    }

    // Scale in
    scaleIn(element, duration = 500) {
        element.style.transform = 'scale(0)';
        element.style.transition = `transform ${duration}ms cubic-bezier(0.68, -0.55, 0.265, 1.55)`;
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 10);
    }

    // Rotate in
    rotateIn(element, duration = 500) {
        element.style.transform = 'rotate(-180deg) scale(0)';
        element.style.transition = `transform ${duration}ms ease`;
        setTimeout(() => {
            element.style.transform = 'rotate(0) scale(1)';
        }, 10);
    }

    // Pulse animation
    pulse(element) {
        element.style.animation = 'pulse 0.5s';
        setTimeout(() => {
            element.style.animation = '';
        }, 500);
    }

    // Flash animation
    flash(element) {
        element.style.animation = 'flash 0.5s';
        setTimeout(() => {
            element.style.animation = '';
        }, 500);
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    @keyframes float-particle {
        0% {
            transform: translateY(0) translateX(0);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateY(-100vh) translateX(${Math.random() * 100 - 50}px);
            opacity: 0;
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
        20%, 40%, 60%, 80% { transform: translateX(10px); }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    @keyframes flash {
        0%, 50%, 100% { opacity: 1; }
        25%, 75% { opacity: 0; }
    }

    .animate-in {
        animation: fadeIn 0.6s ease forwards;
    }
`;
document.head.appendChild(style);

// Initialize animation controller
const animationController = new AnimationController();
window.animationController = animationController;

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AnimationController;
}
