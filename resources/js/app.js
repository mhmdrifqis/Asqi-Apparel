import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import intersect from '@alpinejs/intersect';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Register Alpine plugins
Alpine.plugin(persist);
Alpine.plugin(intersect);

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Make available globally
window.Alpine = Alpine;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

// ========================================
// Alpine.js Global Stores
// ========================================

// Theme Store removed per user request (Light mode only)
// Toast Notification Store
Alpine.store('toast', {
    messages: [],
    
    show(message, type = 'success', duration = 3000) {
        const id = Date.now();
        this.messages.push({ id, message, type });
        setTimeout(() => this.dismiss(id), duration);
    },
    
    success(message) { this.show(message, 'success'); },
    error(message) { this.show(message, 'error', 5000); },
    warning(message) { this.show(message, 'warning', 4000); },
    info(message) { this.show(message, 'info'); },
    
    dismiss(id) {
        this.messages = this.messages.filter(m => m.id !== id);
    }
});

// Cart Store
Alpine.store('cart', {
    count: Alpine.$persist(0).as('asqi_cart_count'),
    
    setCount(n) {
        this.count = n;
        // Trigger bounce animation on cart icon
        const cartIcon = document.getElementById('cart-icon-badge');
        if (cartIcon) {
            cartIcon.classList.add('animate-bounce-once');
            setTimeout(() => cartIcon.classList.remove('animate-bounce-once'), 600);
        }
    },
    
    increment() { this.setCount(this.count + 1); },
    decrement() { if (this.count > 0) this.setCount(this.count - 1); }
});

// Mobile Menu Store
Alpine.store('mobileMenu', {
    open: false,
    toggle() { this.open = !this.open; },
    close() { this.open = false; }
});

// Start Alpine
Alpine.start();

// ========================================
// GSAP Scroll Animations
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    // Fade in elements on scroll
    gsap.utils.toArray('.gsap-fade-in').forEach(el => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 30,
            duration: 0.8,
            ease: 'power2.out'
        });
    });
    
    // Stagger children animation
    gsap.utils.toArray('.gsap-stagger').forEach(container => {
        gsap.from(container.children, {
            scrollTrigger: {
                trigger: container,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 30,
            duration: 0.6,
            stagger: 0.1,
            ease: 'power2.out'
        });
    });
    
    // Parallax hero sections
    gsap.utils.toArray('.gsap-parallax').forEach(el => {
        gsap.to(el, {
            scrollTrigger: {
                trigger: el,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1
            },
            y: -50,
            ease: 'none'
        });
    });
});
