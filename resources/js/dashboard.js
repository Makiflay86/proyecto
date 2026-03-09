function initDashboardCharts() {
    const barCanvas      = document.getElementById('barChart');
    const doughnutCanvas = document.getElementById('doughnutChart');
    if (!barCanvas || !doughnutCanvas) return;

    const dataEl = document.getElementById('chart-data');
    if (!dataEl) return;

    const categoryLabels = JSON.parse(dataEl.dataset.categories || '[]');
    const categoryValues = JSON.parse(dataEl.dataset.values    || '[]');
    const activeCount    = parseInt(dataEl.dataset.active      || '0', 10);
    const inactiveCount  = parseInt(dataEl.dataset.inactive    || '0', 10);

    const palette = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#14b8a6', '#a855f7', '#ec4899', '#0ea5e9'];

    const isDark     = () => document.documentElement.classList.contains('dark');
    const gridColor  = () => isDark() ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
    const labelColor = () => isDark() ? '#9ca3af' : '#6b7280';

    // Destroy previous instances (important when Livewire re-navigates to dashboard)
    if (window._barChart)       window._barChart.destroy();
    if (window._doughnutChart)  window._doughnutChart.destroy();
    if (window._themeObserver)  window._themeObserver.disconnect();

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

    // Re-color charts when the dark class toggles on <html>
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

    window._themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
}

// Runs on initial page load (ES modules execute after DOM is ready)
initDashboardCharts();

// Re-runs after Livewire SPA navigation (wire:navigate)
document.addEventListener('livewire:navigated', initDashboardCharts);
