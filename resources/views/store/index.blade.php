<x-store-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex gap-6" x-data="{ filtersOpen: false }">

            {{-- Sidebar de categorías (desktop) --}}
            <aside class="hidden lg:block w-56 shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sticky top-24">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Categorías</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('store.index', array_filter(request()->except('categoria', 'page'))) }}"
                               class="block px-3 py-2 rounded-lg text-sm transition {{ !request('categoria') ? 'bg-gold-600 text-white font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Todos los productos
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('store.index', array_merge(request()->except('page'), ['categoria' => $category->id])) }}"
                                   class="block px-3 py-2 rounded-lg text-sm transition {{ request('categoria') == $category->id ? 'bg-gold-600 text-white font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                    {{ $category->name }}
                                </a>
                                @if($category->children->isNotEmpty())
                                    <ul class="ml-3 mt-1 space-y-0.5">
                                        @foreach($category->children as $child)
                                            <li>
                                                <a href="{{ route('store.index', array_merge(request()->except('page'), ['categoria' => $child->id])) }}"
                                                   class="block px-3 py-1.5 rounded-lg text-xs transition {{ request('categoria') == $child->id ? 'bg-gold-600 text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            {{-- Contenido principal --}}
            <div class="flex-1 min-w-0">

                {{-- Barra de opciones --}}
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        {{-- Botón filtros (móvil) --}}
                        <button @click="filtersOpen = !filtersOpen"
                                class="lg:hidden flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 8h10M11 12h4"/>
                            </svg>
                            Categorías
                        </button>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $products->total() }}</span>
                            {{ $products->total() === 1 ? 'producto' : 'productos' }}
                        </p>
                    </div>

                    {{-- Ordenar --}}
                    <form action="{{ route('store.index') }}" method="GET" id="sort-form">
                        @foreach(request()->except('orden', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="orden" onchange="document.getElementById('sort-form').submit()"
                                class="text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                            <option value="" {{ !request('orden') ? 'selected' : '' }}>Más recientes</option>
                            <option value="precio_asc" {{ request('orden') === 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                            <option value="precio_desc" {{ request('orden') === 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                        </select>
                    </form>
                </div>

                {{-- Filtros de categorías (móvil, desplegable) --}}
                <div x-show="filtersOpen" x-transition class="lg:hidden mb-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4">
                    <ul class="flex flex-wrap gap-2">
                        <li>
                            <a href="{{ route('store.index', array_filter(request()->except('categoria', 'page'))) }}"
                               class="inline-block px-3 py-1.5 rounded-full text-xs font-medium transition {{ !request('categoria') ? 'bg-gold-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                Todos
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('store.index', array_merge(request()->except('page'), ['categoria' => $category->id])) }}"
                                   class="inline-block px-3 py-1.5 rounded-full text-xs font-medium transition {{ request('categoria') == $category->id ? 'bg-gold-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
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
                        @if(request('buscar') || request('categoria'))
                            <a href="{{ route('store.index') }}" class="mt-4 inline-block text-sm text-gold-500 hover:text-gold-600 font-medium transition">
                                Ver todos los productos
                            </a>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($products as $product)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-gold-900/30 transition-all duration-300">

                                <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
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
                                    </div>

                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $product->nombre }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm line-clamp-2">{{ $product->descripcion }}</p>

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

                    {{-- Paginación --}}
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</x-store-layout>
