<div>
    {{-- Drill-down category filter --}}
    <div class="space-y-2 mb-6">
        @foreach($categoryRows as $depth => $row)
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                {{-- "Todas" / "Todos" button --}}
                @if($depth === 0)
                    <button wire:click="clearFrom(0)"
                            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition
                                {{ empty($path)
                                    ? 'bg-indigo-600 text-white shadow-md'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500' }}">
                        Todas
                    </button>
                @else
                    <button wire:click="clearFrom({{ $depth }})"
                            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition
                                {{ count($path) === $depth
                                    ? 'bg-indigo-600 text-white shadow-md'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500' }}">
                        Todos
                    </button>
                @endif

                {{-- Category pills for this depth --}}
                @foreach($row as $cat)
                    <button wire:click="selectLevel({{ $depth }}, {{ $cat->id }})"
                            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition
                                {{ ($path[$depth] ?? null) === $cat->id
                                    ? 'bg-indigo-600 text-white shadow-md'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- Grid de productos --}}
    @if($products->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center transition-colors duration-300">
            <div class="flex justify-center mb-6">
                <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-6">
                    <svg class="w-12 h-12 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
            </div>
            @if(!empty($path))
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Sin productos en esta categoría</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
                    No hay productos en la categoría seleccionada.
                </p>
                <button wire:click="clearFrom(0)"
                        class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-full shadow-lg hover:bg-indigo-700 transition duration-200 font-medium">
                    Ver todos los productos
                </button>
            @else
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Sin productos todavía</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
                    Aún no has añadido ningún producto. ¡Crea el primero ahora!
                </p>
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-full shadow-lg hover:bg-indigo-700 transition duration-200 font-medium"
                   wire:navigate.hover>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crear primer producto
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-indigo-900/50 transition-all duration-300">
                    <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">Sin imagen</div>
                        @endif
                    </div>

                    <div class="p-5">
                        <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">{{ $product->category?->root->name }}</span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $product->nombre }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm line-clamp-2">{{ $product->descripcion }}</p>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ number_format($product->precio, 2, ",", ".") }}€</span>
                            <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm" wire:navigate.hover>Ver detalles →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
