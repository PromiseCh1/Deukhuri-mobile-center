/**
 * admin.js – Admin panel JavaScript
 * Handles sidebar toggle, dropdown, submenu, and global UI interactions.
 */

document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // 1. SIDEBAR TOGGLE (mobile)
    // ============================================
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('open');
    }
    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('open');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }
    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar);
    }

    // Close sidebar on outside click (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1024) {
            if (sidebar && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                closeSidebar();
            }
        }
    });

    // Close sidebar when window resizes to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            closeSidebar();
        }
    });

    // ============================================
    // 2. USER DROPDOWN TOGGLE
    // ============================================
    const dropdownToggle = document.getElementById('userDropdown');
    const dropdownMenu = document.getElementById('dropdownMenu');

    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }

    // ============================================
    // 3. SUBMENU TOGGLE
    // ============================================
    document.querySelectorAll('.sub-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parentLi = this.closest('li');
            if (!parentLi) return;
            const subMenu = parentLi.querySelector('.sub-menu');
            if (subMenu) {
                subMenu.classList.toggle('open');
                const arrow = this.querySelector('.sub-arrow');
                if (arrow) {
                    arrow.style.transform = subMenu.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            }
        });
    });

    // ============================================
    // 4. ACTIVE NAVIGATION (ensure submenu opens)
    // ============================================
    document.querySelectorAll('.sidebar-nav .sub-menu.open').forEach(function(sub) {
        const parentLi = sub.closest('li');
        if (parentLi) {
            const toggle = parentLi.querySelector('.sub-toggle');
            if (toggle) {
                const arrow = toggle.querySelector('.sub-arrow');
                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                }
            }
        }
    });

    console.log('Admin panel initialized.');
});