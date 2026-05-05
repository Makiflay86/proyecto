<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            {{-- Botón invisible para igualar la altura con las otras páginas que tienen botón de crear --}}
            <div class="h-10"></div>
        </div>
    </x-slot>

    {{-- Chart.js cargado en el <head> una sola vez.
         No se vuelve a cargar en los polls de Livewire porque solo el
         componente DashboardContent se re-renderiza, no el layout. --}}
    @push('head-scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    @endpush

    {{-- El componente Livewire gestiona todos los datos y se auto-refresca
         cada 30 segundos gracias al atributo #[Poll] en DashboardContent.php --}}
    <livewire:admin.dashboard-content />

    @push('scripts')
        @vite('resources/js/dashboard.js')
    @endpush

</x-app-layout>
