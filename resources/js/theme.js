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

// ── Prevención del flash blanco durante wire:navigate ─────────────────────
// Problema: cuando Livewire hace document.body.replaceWith(newBody), los nuevos
// elementos tienen `transition-colors duration-300`. El navegador interpreta la
// inserción como un cambio de "sin fondo" → fondo oscuro y anima la transición,
// produciendo un destello blanco visible.
//
// Solución: inyectar un <style> inline que deshabilita TODAS las transiciones
// justo antes del swap. Al hacerlo desde JS (no desde un archivo SCSS/CSS externo)
// no depende del proceso de compilación de Vite y se aplica de inmediato.
// El <style> se elimina tras dos requestAnimationFrame para que el navegador
// haya pintado el nuevo contenido antes de reactivar las transiciones.
const NO_TRANSITIONS_ID = 'livewire-no-transitions';

function disableTransitions() {
    if (document.getElementById(NO_TRANSITIONS_ID)) return;
    const style = document.createElement('style');
    style.id    = NO_TRANSITIONS_ID;
    style.textContent = '* { transition: none !important; }';
    document.head.appendChild(style);
}

function enableTransitions() {
    // Doble rAF: espera a que el navegador pinte el nuevo DOM antes de
    // reactivar las transiciones (evita que la reactivación en sí genere flash).
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            document.getElementById(NO_TRANSITIONS_ID)?.remove();
        });
    });
}

document.addEventListener('livewire:navigating', disableTransitions);
document.addEventListener('livewire:navigated',  enableTransitions);
