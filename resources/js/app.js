/**
 * app.js
 * Punto de entrada principal del bundle de Vite.
 *
 * - bootstrap.js  → configura Axios con el token CSRF de Laravel
 * - theme.js      → dark/light mode global (applyTheme + toggleTheme)
 *
 * Alpine.js NO se inicializa aquí porque Livewire v4 lo incluye y arranca
 * internamente. Inicializarlo dos veces provoca conflictos con wire:model.
 */

import './bootstrap';
import './theme';

// Alpine.js lo gestiona Livewire internamente — no inicializar manualmente
