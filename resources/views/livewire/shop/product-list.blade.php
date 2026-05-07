<div x-on:set-buscar.window="$wire.set('buscar', $event.detail.value)">
    {{-- Barra de opciones: contador + ordenar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $total }}</span>
            {{ $total === 1 ? 'producto' : 'productos' }}
        </p>

        <select wire:model.live="orden"
                class="w-full sm:w-auto text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
            <option value="">Más recientes</option>
            <option value="precio_asc">Precio: menor a mayor</option>
            <option value="precio_desc">Precio: mayor a menor</option>
        </select>
    </div>

    {{-- Filtro de categorías drill-down --}}
    <div class="space-y-2 mb-6">
        @foreach($categoryRows as $depth => $row)
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">

                @if($depth === 0)
                    <button wire:click="clearFrom(0)"
                            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition
                                {{ empty($path)
                                    ? 'bg-gold-600 text-white shadow-md'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gold-400 dark:hover:border-gold-500' }}">
                        Todas
                    </button>
                @else
                    <button wire:click="clearFrom({{ $depth }})"
                            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition
                                {{ count($path) === $depth
                                    ? 'bg-gold-600 text-white shadow-md'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gold-400 dark:hover:border-gold-500' }}">
                        Todos
                    </button>
                @endif

                @foreach($row as $cat)
                    <button wire:click="selectLevel({{ $depth }}, {{ $cat->id }})"
                            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition
                                {{ ($path[$depth] ?? null) === $cat->id
                                    ? 'bg-gold-600 text-white shadow-md'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gold-400 dark:hover:border-gold-500' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        @endforeach
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
            @if(!empty($path) || !empty($buscar))
                <a href="{{ route('store.index') }}" wire:navigate
                   class="mt-4 inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-full shadow-lg hover:bg-indigo-700 transition duration-200 font-medium text-sm">
                    Limpiar filtros
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-gold-900/30 transition-all duration-300 relative group">

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

                    <div class="p-5">
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

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ number_format($product->precio, 2, ',', '.') }} €</span>
                            <a href="{{ route('store.show', $product) }}"
                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm">
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
