(function () {
  'use strict';

  const toggle = document.getElementById('navToggle');
  const nav    = document.getElementById('mobileNav');
  if (!toggle || !nav) return;

  toggle.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    nav.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
  });

  // Close on outside click (mobile only)
  document.addEventListener('click', (e) => {
    if (!nav.classList.contains('is-open')) return;
    if (nav.contains(e.target) || toggle.contains(e.target)) return;
    nav.classList.remove('is-open');
    nav.hidden = true;
    toggle.setAttribute('aria-expanded', 'false');
  });
})();