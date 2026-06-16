/**
 * E-DSR Theme Toggle — v2.0
 * Manages light / dark mode switching with localStorage persistence.
 * Dispatches a custom event so Chart.js and FullCalendar can re-render.
 *
 * Usage: included via <script defer src="../js/theme-toggle.js">
 * The anti-flash inline script should be placed in the <head> of every page.
 */

(function () {
    'use strict';

    const STORAGE_KEY = 'edsr-theme';
    const DARK  = 'dark';
    const LIGHT = 'light';

    /**
     * Apply a theme to <html> and update Bootstrap 5.3 + window reference.
     * @param {string} theme — 'light' or 'dark'
     */
    function applyTheme(theme) {
        const root = document.documentElement;
        root.setAttribute('data-theme', theme);
        root.setAttribute('data-bs-theme', theme);     // Bootstrap 5.3 native dark mode
        window.EDSR_THEME = theme;

        // Update toggle button icons if they exist
        const btns = document.querySelectorAll('.theme-toggle-btn');
        btns.forEach(btn => {
            const icon = btn.querySelector('i');
            if (icon) {
                if (theme === DARK) {
                    icon.className = 'fa-solid fa-sun';
                    btn.setAttribute('title', 'Switch to Light Mode');
                    btn.setAttribute('aria-label', 'Switch to Light Mode');
                } else {
                    icon.className = 'fa-solid fa-moon';
                    btn.setAttribute('title', 'Switch to Dark Mode');
                    btn.setAttribute('aria-label', 'Switch to Dark Mode');
                }
            }
        });

        // Dispatch custom event so Chart.js / FullCalendar wrappers can react
        document.dispatchEvent(new CustomEvent('edsrThemeChange', { detail: { theme } }));
    }

    /**
     * Resolve which theme to use:
     * 1. Saved localStorage preference
     * 2. OS system preference
     * 3. Fallback → light
     */
    function resolveTheme() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved === DARK || saved === LIGHT) return saved;
        // Respect system preference only when nothing is saved
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return DARK;
        }
        return LIGHT;
    }

    /**
     * Toggle between dark and light and persist the choice.
     */
    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme') || LIGHT;
        const next = current === DARK ? LIGHT : DARK;
        localStorage.setItem(STORAGE_KEY, next);
        applyTheme(next);
    }

    // ─── Init ────────────────────────────────────────────────────────────────

    // Apply theme immediately on script load (prevents flash if not using inline script)
    applyTheme(resolveTheme());

    // Wire up toggle button once DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        const btns = document.querySelectorAll('.theme-toggle-btn');
        btns.forEach(btn => btn.addEventListener('click', toggleTheme));
        // Run applyTheme again to set icon correctly after DOM is available
        applyTheme(resolveTheme());
    });

    // Listen for OS theme change (in case user changes system pref while app is open)
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
            // Only respect system change if user has not saved a manual preference
            if (!localStorage.getItem(STORAGE_KEY)) {
                applyTheme(e.matches ? DARK : LIGHT);
            }
        });
    }

    // Expose toggleTheme globally for inline onclick fallback if needed
    window.edsrToggleTheme = toggleTheme;

})();
