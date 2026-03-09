/**
 * theme.js
 * Gestión global del modo oscuro / claro.
 *
 * Flujo:
 *  1. El script inline en <head> de app.blade.php aplica el tema antes del primer
 *     paint para evitar parpadeo (flash of unstyled content).
 *  2. Este módulo se encarga de mantener el tema correcto en navegaciones SPA
 *     (wire:navigate de Livewire reemplaza el <body> pero no el <html>).
 *  3. toggleTheme() se expone en window para poder llamarlo desde onclick="toggleTheme()"
 *     en cualquier blade sin necesidad de Alpine.
 */

/**
 * Lee la preferencia guardada en localStorage y la aplica al <html>.
 * Si no hay preferencia guardada, respeta prefers-color-scheme del sistema.
 */
function applyTheme() {
    const saved      = localStorage.getItem('theme');
    const preferDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (saved === 'dark' || (!saved && preferDark)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

/**
 * Alterna entre modo oscuro y claro, y guarda la elección en localStorage.
 * Expuesto globalmente para usarlo desde atributos onclick en los blades.
 */
window.toggleTheme = function () {
    const dark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', dark ? 'dark' : 'light');
};

// Livewire wire:navigate reemplaza el <body> en cada navegación SPA.
// Sin este listener, el tema se resetearía al navegar entre páginas.
document.addEventListener('livewire:navigated', applyTheme);
