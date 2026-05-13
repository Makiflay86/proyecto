<div x-data="{ filtersOpen: false }" x-on:set-buscar.window="$wire.set('buscar', $event.detail.value)">

    @php
        $activeCount = (!empty($path) ? 1 : 0)
            + ($precioMin !== '' ? 1 : 0)
            + ($precioMax !== '' ? 1 : 0)
            + ($orden !== '' ? 1 : 0);
    @endphp

    {{-- Barra superior: contador + botón filtros --}}
    <div class="flex items-center justify-between gap-3 mb-4">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $total }}</span>
            {{ $total === 1 ? 'producto' : 'productos' }}
        </p>

        <button @click="filtersOpen = true"
                class="flex items-center gap-2 text-sm font-medium border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-full px-4 py-2 hover:border-gold-400 dark:hover:border-gold-500 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 12h10M11 20h2"/>
            </svg>
            Filtros
            @if($activeCount > 0)
                <span class="bg-gold-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $activeCount }}</span>
            @endif
        </button>
    </div>

    {{-- Backdrop --}}
    <div x-show="filtersOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="filtersOpen = false"
         class="fixed inset-0 bg-black/40 z-40"
         style="display:none"></div>

    {{-- Drawer --}}
    <div x-show="filtersOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed right-0 top-0 bottom-16 sm:bottom-0 w-80 max-w-full bg-white dark:bg-gray-900 z-50 shadow-2xl flex flex-col"
         style="display:none">

        {{-- Cabecera drawer --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700 shrink-0">
            <h3 class="font-bold text-gray-900 dark:text-white">Filtros</h3>
            <button @click="filtersOpen = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Contenido scrollable --}}
        <div class="flex-1 overflow-y-auto px-5 py-5 space-y-7">

            {{-- Ordenar --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Ordenar por</p>
                <div class="flex flex-col gap-2">
                    @foreach(['' => 'Más recientes', 'precio_asc' => 'Precio: menor a mayor', 'precio_desc' => 'Precio: mayor a menor'] as $value => $label)
                        <button wire:click="$set('orden', '{{ $value }}')"
                                class="text-left text-sm px-4 py-2.5 rounded-xl transition font-medium
                                    {{ $orden === $value
                                        ? 'bg-gold-500 text-white'
                                        : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Rango de precio --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Rango de precio</p>
                <div class="flex items-center gap-2">
                    <input type="number"
                           wire:model.live.debounce.500ms="precioMin"
                           placeholder="Mín €"
                           min="0"
                           class="w-full text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                    <span class="text-gray-400 shrink-0">–</span>
                    <input type="number"
                           wire:model.live.debounce.500ms="precioMax"
                           placeholder="Máx €"
                           min="0"
                           class="w-full text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                </div>
            </div>

            {{-- Categorías --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Categoría</p>
                <div class="space-y-2">
                    @foreach($categoryRows as $depth => $row)
                        <div class="flex flex-wrap gap-2">
                            @if($depth === 0)
                                <button wire:click="clearFrom(0)"
                                        class="px-3 py-1.5 rounded-full text-sm font-medium transition
                                            {{ empty($path)
                                                ? 'bg-gold-600 text-white shadow-md'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    Todas
                                </button>
                            @else
                                <button wire:click="clearFrom({{ $depth }})"
                                        class="px-3 py-1.5 rounded-full text-sm font-medium transition
                                            {{ count($path) === $depth
                                                ? 'bg-gold-600 text-white shadow-md'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    Todos
                                </button>
                            @endif
                            @foreach($row as $cat)
                                <button wire:click="selectLevel({{ $depth }}, {{ $cat->id }})"
                                        class="px-3 py-1.5 rounded-full text-sm font-medium transition
                                            {{ ($path[$depth] ?? null) === $cat->id
                                                ? 'bg-gold-600 text-white shadow-md'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Footer drawer --}}
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex gap-3 shrink-0">
            <button wire:click="clearAll"
                    class="flex-1 text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Limpiar
            </button>
            <button @click="filtersOpen = false"
                    class="flex-1 text-sm font-medium bg-gold-500 hover:bg-gold-600 text-white py-2.5 rounded-xl transition">
                Ver {{ $total }} {{ $total === 1 ? 'resultado' : 'resultados' }}
            </button>
        </div>

    </div>

    {{-- Grid de productos --}}
    @if($products->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
            <div class="flex justify-center mb-6">
                <div class="bg-gold-100 dark:bg-gold-900/30 rounded-full p-6">
                    <svg class="w-12 h-12 text-gold-400 dark:text-gold-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">No hay productos disponibles</h3>
            @if(!empty($path) || !empty($buscar) || $precioMin !== '' || $precioMax !== '')
                <button wire:click="clearAll"
                        class="mt-4 inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-full shadow-lg hover:bg-indigo-700 transition duration-200 font-medium text-sm">
                    Limpiar filtros
                </button>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-gold-900/30 transition-all duration-300 relative group flex flex-col">

                    {{-- Badges centrados --}}
                    @if($product->isSold() || $product->isReserved())
                        <div class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none">
                            @if($product->isSold())
                                <span class="bg-red-600/90 dark:bg-red-700/90 text-white text-sm font-black uppercase tracking-widest px-6 py-2 rounded-lg shadow-2xl backdrop-blur-sm transform -rotate-12 border-2 border-white/20">Vendido</span>
                            @else
                                <span class="bg-amber-500/90 dark:bg-amber-600/90 text-white text-sm font-black uppercase tracking-widest px-6 py-2 rounded-lg shadow-2xl backdrop-blur-sm transform -rotate-12 border-2 border-white/20">Reservado</span>
                            @endif
                        </div>
                    @endif

                    <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-700 {{ $product->isSold() || $product->isReserved() ? 'opacity-60 grayscale-[0.5]' : '' }}">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                 alt="{{ $product->nombre }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">Sin imagen</div>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 truncate">
                                {{ $product->category?->root->name }}
                            </span>
                            @livewire('shop.toggle-like', ['productId' => $product->id], key('store-'.$product->id))
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $product->nombre }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm line-clamp-2">{{ $product->descripcion }}</p>

                        @if($product->user)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                Por <a href="{{ route('users.profile', $product->user) }}"
                                       class="font-medium text-gray-600 dark:text-gray-400 hover:text-gold-500 dark:hover:text-gold-400 transition">
                                    {{ $product->user->name }}
                                </a>
                            </p>
                        @endif

                        <div class="mt-auto pt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ number_format($product->precio, 2, ',', '.') }} €</span>
                            <a href="{{ route('store.show', $product) }}"
                               class="text-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 px-3 py-2 rounded-lg transition">
                                Ver detalles →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Scroll infinito --}}
        @if($hasMore)
            <div class="mt-8 flex flex-col items-center gap-4">
                <div
                    x-data
                    x-init="
                        new IntersectionObserver(([entry]) => {
                            if (entry.isIntersecting) $wire.loadMore()
                        }, { rootMargin: '400px' }).observe($el)
                    "
                    class="h-1 w-full"
                ></div>
                <div class="w-7 h-7 border-2 border-gold-400 border-t-transparent rounded-full animate-spin"></div>
            </div>
        @endif
    @endif
</div>
