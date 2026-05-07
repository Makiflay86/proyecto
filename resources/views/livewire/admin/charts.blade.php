<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Estadísticas') }}
            </h2>
            {{-- Botón invisible para igualar la altura con las otras páginas que tienen botón de crear --}}
            <div class="h-10"></div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Gráfico de Categorías Padre --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-6 transition-colors duration-300">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-6 uppercase tracking-wide">
                    Productos por Categoría Padre
                </h3>
                <div wire:ignore class="relative h-80">
                    <canvas id="adminRootBarChart"></canvas>
                </div>
            </div>

            {{-- Tabla Comparativa --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden transition-colors duration-300">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                        Desglose Detallado
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Categoría Padre</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">Activos</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-amber-600 dark:text-amber-400 uppercase tracking-wider">Reservados</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">Vendidos</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inactivos</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($statsByCategory as $name => $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white whitespace-nowrap">
                                    {{ $name }}
                                </td>
                                <td class="px-6 py-4 text-center font-black text-gold-600 dark:text-gold-400">
                                    {{ $data['total'] }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">
                                    {{ $data['activo'] }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">
                                    {{ $data['reservado'] }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">
                                    {{ $data['vendido'] }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">
                                    {{ $data['inactivo'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Script para inicializar el gráfico --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            const canvas = document.getElementById('adminRootBarChart');
            if (!canvas) return;

            const labels = @json($chartLabels);
            const values = @json($chartValues);
            
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
            const labelColor = isDark ? '#9ca3af' : '#6b7280';
            const palette = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#14b8a6', '#a855f7', '#ec4899', '#0ea5e9'];

            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Productos',
                        data: values,
                        backgroundColor: palette.slice(0, labels.length),
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: labelColor } },
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: labelColor, stepSize: 1 } }
                    }
                }
            });
        }, { once: true });
    </script>
    @endpush
</div>
