/**
 * dashboard.js
 * Inicialización y actualización de los gráficos Chart.js del dashboard.
 *
 * Flujo general:
 *  1. initDashboardCharts() crea los gráficos al cargar la página.
 *  2. watchChartData() instala un MutationObserver sobre #chart-data.
 *     Cuando el componente Livewire DashboardContent hace un poll (cada 30 s),
 *     actualiza los data-attributes de ese div. El observer lo detecta y
 *     llama a updateCharts() para reflejar los nuevos datos SIN destruir los
 *     canvas (evita el parpadeo y el "Canvas already in use" de Chart.js).
 *  3. Los contenedores de los canvas tienen wire:ignore en el blade, por lo
 *     que Livewire nunca toca los elementos <canvas> directamente.
 *
 * Requiere: Chart.js cargado vía CDN en el <head> (pushado desde dashboard.blade.php)
 */

// Paleta de colores para las barras — definida en el módulo para reutilizarla
// tanto en la creación inicial como en actualizaciones por poll.
const palette = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#14b8a6', '#a855f7', '#ec4899', '#0ea5e9'];

// Helpers para adaptar colores al tema activo en cada render
const isDark     = () => document.documentElement.classList.contains('dark');
const gridColor  = () => isDark() ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
const labelColor = () => isDark() ? '#9ca3af' : '#6b7280';

/**
 * Lee los data-attributes de #chart-data y devuelve los valores ya parseados.
 * Centraliza la lectura para no repetir JSON.parse en múltiples sitios.
 */
function readChartData() {
    const el = document.getElementById('chart-data');
    if (!el) return null;

    return {
        categoryLabels: JSON.parse(el.dataset.categories || '[]'),
        categoryValues: JSON.parse(el.dataset.values     || '[]'),
        activeCount:    parseInt(el.dataset.active        || '0', 10),
        inactiveCount:  parseInt(el.dataset.inactive      || '0', 10),
    };
}

/**
 * Actualiza los datos de los gráficos existentes sin destruirlos.
 * Llamada por el MutationObserver cuando Livewire hace un poll y cambia
 * los data-attributes de #chart-data.
 * Usa mode 'none' para que no haya animación en el refresco automático.
 */
function updateCharts() {
    const data = readChartData();
    if (!data || !window._barChart || !window._doughnutChart) return;

    const { categoryLabels, categoryValues, activeCount, inactiveCount } = data;

    // Actualizar gráfico de barras
    window._barChart.data.labels                      = categoryLabels.length ? categoryLabels : ['Sin datos'];
    window._barChart.data.datasets[0].data            = categoryValues.length ? categoryValues : [0];
    window._barChart.data.datasets[0].backgroundColor = palette.slice(0, Math.max(categoryValues.length, 1));
    window._barChart.update('none'); // sin animación para que el refresco sea silencioso

    // Actualizar gráfico doughnut
    window._doughnutChart.data.datasets[0].data = [activeCount, inactiveCount];
    window._doughnutChart.update('none');
}

/**
 * Instala un MutationObserver sobre #chart-data para detectar cuándo
 * Livewire actualiza los data-attributes (en cada poll de 30 s) y
 * refrescar los gráficos sin necesidad de recrearlos.
 */
function watchChartData() {
    const el = document.getElementById('chart-data');
    if (!el) return;

    // Disconnectar el observer previo si existía (p. ej. al volver al dashboard)
    if (window._chartDataObserver) window._chartDataObserver.disconnect();

    window._chartDataObserver = new MutationObserver(updateCharts);
    window._chartDataObserver.observe(el, { attributes: true });
}

/**
 * Crea (o recrea) los dos gráficos del dashboard.
 * Es seguro llamarla múltiples veces: destruye las instancias previas antes de crear.
 * Se llama en la carga inicial y en cada navegación SPA (livewire:navigated).
 */
function initDashboardCharts() {
    const barCanvas      = document.getElementById('barChart');
    const doughnutCanvas = document.getElementById('doughnutChart');

    // Si no estamos en el dashboard, salir sin error
    if (!barCanvas || !doughnutCanvas) return;

    const data = readChartData();
    if (!data) return;

    const { categoryLabels, categoryValues, activeCount, inactiveCount } = data;

    // Destruir instancias previas para evitar el error "Canvas already in use"
    if (window._barChart)           window._barChart.destroy();
    if (window._doughnutChart)      window._doughnutChart.destroy();
    if (window._themeObserver)      window._themeObserver.disconnect();
    if (window._chartDataObserver)  window._chartDataObserver.disconnect();

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
    window._themeObserver = new MutationObserver(() => {
        [window._barChart, window._doughnutChart].forEach(chart => {
            if (!chart) return;

            if (chart.options.scales) {
                Object.values(chart.options.scales).forEach(axis => {
                    if (axis.grid)  axis.grid.color  = gridColor();
                    if (axis.ticks) axis.ticks.color = labelColor();
                });
            }

            if (chart.options.plugins?.legend?.labels) {
                chart.options.plugins.legend.labels.color = labelColor();
            }

            chart.update();
        });
    });

    window._themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });

    // ── Observer para actualizar datos en los polls de Livewire ───
    watchChartData();
}

// Carga inicial
initDashboardCharts();

// Navegaciones SPA: Livewire reemplaza el body y dispara este evento
document.addEventListener('livewire:navigated', initDashboardCharts);
