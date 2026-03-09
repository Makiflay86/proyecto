/**
 * dashboard.js
 * Inicialización de los gráficos Chart.js del dashboard.
 *
 * Los datos de PHP (categorías, conteos, activos/inactivos) se pasan sin código
 * inline usando data-attributes en el div#chart-data del blade. Esto mantiene
 * la vista limpia y desacopla PHP de JS.
 *
 * Se ejecuta tanto en la carga inicial como en cada navegación SPA de Livewire
 * (livewire:navigated), destruyendo las instancias anteriores para evitar
 * que Chart.js se queje de canvas ya en uso.
 *
 * Requiere: Chart.js cargado vía CDN en el <head> (pushado desde dashboard.blade.php)
 */

/**
 * Crea (o recrea) los dos gráficos del dashboard.
 * Es seguro llamarla múltiples veces: destruye las instancias previas antes de crear.
 */
function initDashboardCharts() {
    const barCanvas      = document.getElementById('barChart');
    const doughnutCanvas = document.getElementById('doughnutChart');

    // Si no estamos en el dashboard, salir sin error
    if (!barCanvas || !doughnutCanvas) return;

    // Leer los datos pasados por PHP a través de data-attributes
    const dataEl = document.getElementById('chart-data');
    if (!dataEl) return;

    const categoryLabels = JSON.parse(dataEl.dataset.categories || '[]');
    const categoryValues = JSON.parse(dataEl.dataset.values    || '[]');
    const activeCount    = parseInt(dataEl.dataset.active      || '0', 10);
    const inactiveCount  = parseInt(dataEl.dataset.inactive    || '0', 10);

    // Paleta de colores para las barras por categoría
    const palette = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#14b8a6', '#a855f7', '#ec4899', '#0ea5e9'];

    // Helpers para adaptar colores al tema activo en cada render/update
    const isDark     = () => document.documentElement.classList.contains('dark');
    const gridColor  = () => isDark() ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
    const labelColor = () => isDark() ? '#9ca3af' : '#6b7280';

    // Destruir instancias previas para evitar el error "Canvas already in use"
    // cuando Livewire navega de vuelta al dashboard
    if (window._barChart)      window._barChart.destroy();
    if (window._doughnutChart) window._doughnutChart.destroy();
    if (window._themeObserver) window._themeObserver.disconnect();

    // ── Gráfico de barras: productos por categoría ───────────────
    window._barChart = new Chart(barCanvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: categoryLabels.length ? categoryLabels : ['Sin datos'],
            datasets: [{
                label: 'Productos',
                data: categoryValues.length ? categoryValues : [0],
                backgroundColor: palette.slice(0, Math.max(categoryValues.length, 1)),
                borderRadius: 6,
                borderSkipped: false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: gridColor() }, ticks: { color: labelColor(), font: { size: 11 } } },
                y: { beginAtZero: true, grid: { color: gridColor() }, ticks: { color: labelColor(), stepSize: 1, font: { size: 11 } } },
            },
        },
    });

    // ── Gráfico doughnut: productos activos vs inactivos ─────────
    window._doughnutChart = new Chart(doughnutCanvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Activos', 'Inactivos'],
            datasets: [{
                data: [activeCount, inactiveCount],
                backgroundColor: ['#22c55e', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { color: labelColor(), padding: 16, font: { size: 12 } } },
            },
        },
    });

    // ── Observer para re-colorear los gráficos al cambiar el tema ─
    // Observa cambios en el atributo class del <html> (donde vive la clase .dark)
    window._themeObserver = new MutationObserver(() => {
        [window._barChart, window._doughnutChart].forEach(chart => {
            if (!chart) return;

            // Actualizar colores de ejes
            if (chart.options.scales) {
                Object.values(chart.options.scales).forEach(axis => {
                    if (axis.grid)  axis.grid.color  = gridColor();
                    if (axis.ticks) axis.ticks.color = labelColor();
                });
            }

            // Actualizar color de la leyenda
            if (chart.options.plugins?.legend?.labels) {
                chart.options.plugins.legend.labels.color = labelColor();
            }

            chart.update();
        });
    });

    window._themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'], // solo reaccionar a cambios de clase, no otros atributos
    });
}

// Carga inicial: los módulos ES ejecutan después de que el DOM está listo
initDashboardCharts();

// Navegaciones SPA posteriores: Livewire reemplaza el body y dispara este evento
document.addEventListener('livewire:navigated', initDashboardCharts);
