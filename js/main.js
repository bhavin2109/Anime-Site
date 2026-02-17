/**
 * main.js — Core interactivity for Anime Site
 * Scroll animations, navbar effects, mobile menu, search enhancements
 */

document.addEventListener('DOMContentLoaded', () => {
    // Mark page as loaded (triggers CSS page-load animation)
    document.body.classList.add('page-loaded');

    // === Scroll Reveal (IntersectionObserver) ===
    initScrollReveal();

    // === Navbar Scroll Effect ===
    initNavbarScroll();

    // === Mobile Hamburger Menu ===
    initMobileMenu();

    // === Search Enhancements ===
    initSearchEnhancements();

    // === Stagger animation for grids ===
    initStaggerAnimations();
});

// ============================================
// Scroll Reveal
// ============================================
function initScrollReveal() {
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .stagger-children');

    if (revealElements.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                // Once revealed, stop observing for performance
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(el => observer.observe(el));
}

// ============================================
// Navbar Scroll Effect
// ============================================
function initNavbarScroll() {
    const header = document.querySelector('header');
    if (!header) return;

    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        if (currentScrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScrollY = currentScrollY;
    }, { passive: true });
}

// ============================================
// Mobile Hamburger Menu
// ============================================
function initMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('header nav');

    if (!hamburger || !navMenu) return;

    hamburger.addEventListener('click', (e) => {
        e.stopPropagation();
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('open');
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
            hamburger.classList.remove('active');
            navMenu.classList.remove('open');
        }
    });

    // Close menu when clicking a link
    navMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('open');
        });
    });
}

// ============================================
// Search Enhancements
// ============================================
function initSearchEnhancements() {
    const searchInput = document.getElementById('searchQuery');
    if (!searchInput) return;

    // Enter key to search
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (typeof performSearch === 'function') {
                performSearch();
            }
        }
    });

    // Focus animation
    searchInput.addEventListener('focus', () => {
        searchInput.parentElement.style.transform = 'scale(1.02)';
    });

    searchInput.addEventListener('blur', () => {
        searchInput.parentElement.style.transform = 'scale(1)';
    });
}

// ============================================
// Stagger Animations for Grids
// ============================================
function initStaggerAnimations() {
    // Add reveal class to common elements automatically
    const autoRevealSelectors = [
        '.genre-box',
        '.feature-card',
        '.team-card',
        '.section-title'
    ];

    autoRevealSelectors.forEach(selector => {
        document.querySelectorAll(selector).forEach((el, index) => {
            if (!el.classList.contains('reveal') && !el.classList.contains('revealed')) {
                el.classList.add('reveal');
                el.style.transitionDelay = `${Math.min(index * 0.05, 0.5)}s`;
            }
        });
    });

    // Re-init scroll reveal for newly added elements
    initScrollReveal();
}

// ============================================
// Smooth Scroll for Anchor Links
// ============================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
