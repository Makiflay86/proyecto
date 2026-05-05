{{--
    dashboard-content.blade.php
    Contenido dinámico del dashboard, renderizado por el componente Livewire DashboardContent.

    Se refresca automáticamente cada 5 s gracias a wire:poll.5000ms en el div raíz.

    Gráficos:
      - #chart-data  → Livewire actualiza sus data-attributes en cada poll.
      - canvas        → Envueltos en wire:ignore para que Livewire no los toque.
      - dashboard.js  → MutationObserver en #chart-data actualiza los gráficos
                        sin destruir/recrear las instancias de Chart.js.
--}}
{{-- wire:poll.5000ms: Livewire re-renderiza este componente cada 5 segundos,
     actualizando la hora, los stats y la tabla de productos recientes. --}}
<div wire:poll.5000ms>

    {{-- Datos de PHP pasados al JS mediante data-attributes.
         No usa wire:ignore → Livewire actualiza estos valores en cada poll.
         dashboard.js los observa con MutationObserver. --}}
    <div id="chart-data" class="hidden"
         data-categories="{{ json_encode($productsByCategory->keys()->values()) }}"
         data-values="{{ json_encode($productsByCategory->values()->values()) }}"
         data-active="{{ $activeProducts }}"
         data-reserved="{{ $reservedProducts }}"
         data-sold="{{ $soldProducts }}"
         data-inactive="{{ $inactiveProducts }}">
    </div>

    <div class="py-8 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── Welcome banner ── --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 transition-colors duration-300">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        ¡Bienvenido, <span class="text-indigo-600 dark:text-indigo-400">{{ Auth::user()->name }}</span>!
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Hoy es <strong>{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</strong>
                    </p>
                </div>
                {{-- La hora se actualiza en cada poll --}}
                <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-full font-medium self-start sm:self-center">
                    {{ now()->format('H:i') }} hrs
                </span>
            </div>

            {{-- ── Daily message ── --}}
            @if($message)
            <div class="bg-indigo-50 dark:bg-indigo-900/40 border border-indigo-200 dark:border-indigo-700 shadow-sm sm:rounded-xl p-5 flex items-start gap-3 transition-colors duration-300">
                <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                <p class="text-indigo-800 dark:text-indigo-200 text-sm font-medium">{{ $message }}</p>
            </div>
            @endif

            {{-- ── Stats cards ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-4 flex flex-col items-center text-center gap-2 transition-colors duration-300 border border-transparent hover:border-indigo-200 dark:hover:border-indigo-800">
                    <div class="bg-indigo-100 dark:bg-indigo-900/50 rounded-lg p-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Total</p>
                        <p class="text-xl font-black text-gray-800 dark:text-white">{{ $totalProducts }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-4 flex flex-col items-center text-center gap-2 transition-colors duration-300 border border-transparent hover:border-green-200 dark:hover:border-green-800">
                    <div class="bg-green-100 dark:bg-green-900/50 rounded-lg p-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Activos</p>
                        <p class="text-xl font-black text-gray-800 dark:text-white">{{ $activeProducts }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-4 flex flex-col items-center text-center gap-2 transition-colors duration-300 border border-transparent hover:border-amber-200 dark:hover:border-amber-800">
                    <div class="bg-amber-100 dark:bg-amber-900/50 rounded-lg p-2">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Reservados</p>
                        <p class="text-xl font-black text-gray-800 dark:text-white">{{ $reservedProducts }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-4 flex flex-col items-center text-center gap-2 transition-colors duration-300 border border-transparent hover:border-red-200 dark:hover:border-red-800">
                    <div class="bg-red-100 dark:bg-red-900/50 rounded-lg p-2">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Vendidos</p>
                        <p class="text-xl font-black text-gray-800 dark:text-white">{{ $soldProducts }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-4 flex flex-col items-center text-center gap-2 transition-colors duration-300 border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Inactivos</p>
                        <p class="text-xl font-black text-gray-800 dark:text-white">{{ $inactiveProducts }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-4 flex flex-col items-center text-center gap-2 transition-colors duration-300 border border-transparent hover:border-yellow-200 dark:hover:border-yellow-800">
                    <div class="bg-yellow-100 dark:bg-yellow-900/50 rounded-lg p-2">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Categorías</p>
                        <p class="text-xl font-black text-gray-800 dark:text-white">{{ $categoriesCount }}</p>
                    </div>
                </div>

            </div>

            {{-- ── Charts row ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{--
                    wire:ignore: Livewire no toca el canvas en los polls.
                    Los datos se actualizan en JS vía MutationObserver sobre #chart-data.
                --}}
                <div wire:ignore class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-6 transition-colors duration-300">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wide">
                        Productos por categoría
                    </h3>
                    <div class="relative h-56">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>

                <div wire:ignore class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-6 transition-colors duration-300">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wide">
                        Estado de productos
                    </h3>
                    <div class="relative h-56 flex items-center justify-center">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- ── Recent products table ── --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden transition-colors duration-300">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                        Productos recientes
                    </h3>
                    <a href="{{ route('products.index') }}"
                       class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                        Ver todos →
                    </a>
                </div>

                @if($recentProducts->isEmpty())
                    <div class="p-8 text-center text-gray-400 dark:text-gray-500 text-sm">
                        No hay productos registrados aún.
                    </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Creado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($recentProducts as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-white whitespace-nowrap">
                                    <a href="{{ route('products.show', $product) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $product->nombre }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 whitespace-nowrap text-xs">
                                    {{ $product->category?->root->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200 whitespace-nowrap font-bold">
                                    {{ number_format($product->precio, 2, ',', '.') }}€
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->isSold())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400">
                                            Vendido
                                        </span>
                                    @elseif($product->isReserved())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400">
                                            Reservado
                                        </span>
                                    @elseif($product->estado === 'activo')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                                            {{ ucfirst($product->estado) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-500 whitespace-nowrap text-xs">
                                    {{ $product->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

        </div>
    </div>

</div>
