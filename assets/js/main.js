/**
 * main.js – Responsive hamburger menu functionality
 * Handles mobile navigation toggle, closing, and accessibility.
 * Requires #hamburger button and #main-nav nav element in header.php.
 */

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const mainNav = document.getElementById('main-nav');
    const navLinks = mainNav ? mainNav.querySelectorAll('a') : [];

    // Exit if required elements are missing
    if (!hamburger || !mainNav) return;

    // Toggle menu on hamburger click
    function toggleMenu() {
        const isExpanded = hamburger.getAttribute('aria-expanded') === 'true';
        mainNav.classList.toggle('show');
        hamburger.setAttribute('aria-expanded', !isExpanded);
    }

    // Close menu (if open)
    function closeMenu() {
        if (mainNav.classList.contains('show')) {
            mainNav.classList.remove('show');
            hamburger.setAttribute('aria-expanded', 'false');
        }
    }

    // Event: hamburger click
    hamburger.addEventListener('click', toggleMenu);

    // Event: close menu when any navigation link is clicked
    navLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    // Event: close menu if user clicks outside the nav / hamburger
    document.addEventListener('click', function(event) {
        const isClickInside = mainNav.contains(event.target) || hamburger.contains(event.target);
        if (!isClickInside && mainNav.classList.contains('show')) {
            closeMenu();
        }
    });

    // Event: reset menu state on window resize (avoids stuck open state)
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && mainNav.classList.contains('show')) {
            closeMenu();
        }
    });

    // Set initial ARIA state
    hamburger.setAttribute('aria-expanded', 'false');
    hamburger.setAttribute('aria-label', 'Menu');
});