/**
 * theme.js – Light/Dark Mode Toggle
 * 
 * - Default mode: 'light'
 * - Does NOT follow OS preference automatically.
 * - Saves user preference in localStorage.
 * - Only applies dark mode if user explicitly toggles it.
 * - If the theme toggle button exists (#theme-toggle), it updates its label.
 */

(function () {
  const STORAGE_KEY = 'theme';

  // Apply the given mode ('light' or 'dark')
  function applyTheme(mode) {
    const isDark = mode === 'dark';
    document.body.classList.toggle('dark-mode', isDark);

    // Update toggle button text/icon if it exists
    const btn = document.getElementById('theme-toggle');
    if (btn) {
      btn.textContent = isDark ? '☀️' : '🌙';
      btn.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
    }
  }

  // Get saved preference; default to 'light'
  const savedTheme = localStorage.getItem(STORAGE_KEY) || 'light';

  // Apply theme on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    applyTheme(savedTheme);

    const toggleBtn = document.getElementById('theme-toggle');
    if (!toggleBtn) {
      // If no toggle button, we only apply the saved theme; no interaction needed.
      // You can optionally add a button via HTML – ensure it has id="theme-toggle"
      return;
    }

    // Toggle on click
    toggleBtn.addEventListener('click', function () {
      const currentIsDark = document.body.classList.contains('dark-mode');
      const nextMode = currentIsDark ? 'light' : 'dark';
      localStorage.setItem(STORAGE_KEY, nextMode);
      applyTheme(nextMode);
    });
  });
})();