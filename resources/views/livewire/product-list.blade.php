<div>
    {{-- Barra de opciones: contador + ordenar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $products->total() }}</span>
            {{ $products->total() === 1 ? 'producto' : 'productos' }}
        </p>

        <select wire:model.live="orden"
                class="w-full sm:w-auto text-sm border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
            <option value="">Más recientes</option>
            <option value="precio_asc">Precio: menor a mayor</option>
            <option value="precio_desc">Precio: mayor a menor</option>
        </select>
    </div>

    {{--
        FILTRO DE CATEGORÍAS EN MODO DRILL-DOWN
        =========================================
        "Drill-down" significa que el usuario navega por niveles de categorías:
        primero elige la raíz (MOTOR), luego aparece una fila con sus hijos (COCHE, MOTO),
        al elegir uno aparece otra fila con sus hijos (SEDAN, SUV)... y así.

        $categoryRows es un array de colecciones, una por nivel:
          $categoryRows[0] → categorías raíz (MOTOR, ROPA, ELECTRÓNICA...)
          $categoryRows[1] → hijos de la raíz seleccionada (COCHE, MOTO si eligió MOTOR)
          $categoryRows[2] → hijos del nivel 1 seleccionado... y así

        $path es el array de IDs seleccionados: [id_raiz, id_hijo, id_nieto...]
        Se usa para saber qué botón debe aparecer resaltado en indigo.
    --}}
    <div class="space-y-2 mb-6">
        @foreach($categoryRows as $depth => $row)
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">

                {{--
                    BOTÓN "TODAS/TODOS" DE CADA FILA
                    Al hacer click llama al método clearFrom($depth) del componente PHP.
                    - En depth 0: "Todas" → limpia todo el filtro (muestra todos los productos)
                    - En depth 1+: "Todos" → quita la selección de este nivel hacia abajo

                    Lógica de resaltado:
                    - Fila 0: resaltado si $path está vacío (no hay nada seleccionado)
                    - Fila N: resaltado si count($path) === N (el path termina antes de este nivel)
                --}}
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

                {{--
                    BOTONES DE CATEGORÍA DE ESTE NIVEL
                    wire:click="selectLevel($depth, $cat->id)" llama al método PHP del componente
                    que actualiza $path y Livewire re-renderiza el componente automáticamente.

                    Lógica de resaltado: ($path[$depth] ?? null) === $cat->id
                    - $path[$depth] obtiene el ID seleccionado en este nivel
                    - ?? null evita error si ese índice no existe
                    - === compara de forma estricta (mismo valor Y mismo tipo)
                    - IMPORTANTE: los valores del path son integers (gracias al cast en mount())
                      y $cat->id también es integer, por eso === funciona correctamente.
                      Sin ese cast, los valores de la URL llegarían como strings y === fallaría.
                --}}
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

    {{-- GRID DE PRODUCTOS --}}
    @if($products->isEmpty())
        {{--
            ESTADO VACÍO
            Diferenciamos dos casos:
            1. Hay filtro activo pero no hay productos → ofrecemos quitar el filtro
            2. No hay filtro y no hay productos en absoluto → ofrecemos crear el primero
        --}}
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
                {{-- wire:click llama al método PHP sin recargar la página --}}
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
        {{-- GRID DE TARJETAS DE PRODUCTOS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-indigo-900/50 transition-all duration-300 relative group">

                    {{-- Badges centrados (Estilo tienda) --}}
                    @if($product->isSold() || $product->isReserved() || $product->estado === 'inactivo')
                        <div class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none">
                            @if($product->isSold())
                                <span class="bg-red-600/90 dark:bg-red-700/90 text-white text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-lg shadow-2xl backdrop-blur-sm transform -rotate-12 border-2 border-white/20">Vendido</span>
                            @elseif($product->isReserved())
                                <span class="bg-amber-500/90 dark:bg-amber-600/90 text-white text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-lg shadow-2xl backdrop-blur-sm transform -rotate-12 border-2 border-white/20">Reservado</span>
                            @else
                                <span class="bg-gray-800/90 dark:bg-gray-700/90 text-white text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-lg shadow-2xl backdrop-blur-sm transform -rotate-12 border-2 border-white/20">Inactivo</span>
                            @endif
                        </div>
                    @endif

                    {{-- IMAGEN DEL PRODUCTO --}}
                    <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-700 {{ $product->isSold() || $product->isReserved() || $product->estado === 'inactivo' ? 'opacity-60 grayscale-[0.5]' : '' }}">
                        @if($product->images->isNotEmpty())
                            {{-- Mostramos la primera imagen de la colección --}}
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">Sin imagen</div>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 truncate">{{ $product->category?->root->name }}</span>
                            @if($product->estado === 'activo')
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 dark:bg-green-400"></span>
                                    Activo
                                </span>
                            @elseif($product->isSold())
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 dark:bg-red-400"></span>
                                    Vendido
                                </span>
                            @elseif($product->isReserved())
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 dark:bg-amber-400"></span>
                                    Reservado
                                </span>
                            @else
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                                    {{ ucfirst($product->estado) }}
                                </span>
                            @endif
                        </div>

                        {{-- Nombre del producto --}}
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $product->nombre }}</h3>

                        {{--
                            Descripción truncada a 2 líneas con line-clamp-2 (clase de Tailwind).
                            Evita que las descripciones largas rompan el layout de la grid.
                        --}}
                        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm line-clamp-2">{{ $product->descripcion }}</p>

                        {{-- PRECIO + ENLACE AL DETALLE --}}
                        <div class="mt-4 flex items-center justify-between">
                            {{--
                                number_format formatea el precio con 2 decimales,
                                coma como separador decimal y punto como separador de miles.
                                Ejemplo: 1234.5 → "1.234,50€"
                            --}}
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ number_format($product->precio, 2, ",", ".") }}€</span>
                            <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm" wire:navigate.hover>Ver detalles →</a>
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
