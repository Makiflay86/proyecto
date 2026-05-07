<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{--
                BREADCRUMB DE NAVEGACIÓN
                Muestra la ruta jerárquica: Categorías › MOTOR › COCHE
                Si la categoría tiene padre, lo mostramos como enlace intermedio.
                $category->parent viene cargado desde el controlador con ->load(['parent', ...])
            --}}
            <nav class="flex items-center gap-1.5 text-sm mb-6 ps-4 flex-wrap">
                <a href="{{ route('categories.index') }}"
                   class="text-gold-600 dark:text-gold-400 hover:text-gold-700 dark:hover:text-gold-300 font-medium"
                   wire:navigate.hover>Categorías</a>
                {{-- Recorremos todos los ancestros: Electrónica › Móvil › Apple --}}
                @foreach($ancestors as $ancestor)
                    <span class="text-gray-400">›</span>
                    <a href="{{ route('categories.show', $ancestor) }}"
                       class="text-gold-600 dark:text-gold-400 hover:text-gold-700 dark:hover:text-gold-300 font-medium"
                       wire:navigate.hover>{{ $ancestor->name }}</a>
                @endforeach
                <span class="text-gray-400">›</span>
                <span class="text-gray-600 dark:text-gray-400">{{ $category->name }}</span>
            </nav>

            {{-- TARJETA PRINCIPAL DE LA CATEGORÍA --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 transition-colors duration-300">

                {{--
                    CABECERA: imagen/icono + nombre + conteo de productos
                    x-data="{ imgOpen: false }" inicializa un estado local de Alpine.js
                    para controlar si el modal de imagen está abierto o cerrado.
                --}}
                <div class="flex items-center gap-4" x-data="{ imgOpen: false }">

                    {{-- IMAGEN DE LA CATEGORÍA --}}
                    @if($category->image)
                        {{--
                            Si tiene imagen, al hacer click abre un modal con la imagen grande.
                            @click="imgOpen = true" es Alpine.js: cambia la variable local.
                            group + group-hover permite aplicar estilos al hijo cuando se hace
                            hover sobre el padre.
                        --}}
                        <button type="button" @click="imgOpen = true" class="shrink-0 group relative">
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 class="w-20 h-20 rounded-xl object-cover border border-gray-200 dark:border-gray-600 shadow-sm transition group-hover:brightness-90 cursor-zoom-in">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-6 h-6 text-white drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0zm0 0l4 4"/>
                                </svg>
                            </div>
                        </button>

                        {{--
                            MODAL DE IMAGEN AMPLIADA
                            x-show controla la visibilidad con Alpine.js.
                            x-transition añade animaciones de entrada/salida.
                            @keydown.escape.window="imgOpen = false" permite cerrarlo con Escape.
                            @click.stop en el contenedor interior evita que el click se propague
                            al fondo (que cierra el modal).
                        --}}
                        <div x-show="imgOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="imgOpen = false"
                             @keydown.escape.window="imgOpen = false"
                             class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-gray-900/75 cursor-zoom-out">
                            <div @click.stop class="bg-gray-100 dark:bg-gray-700 rounded-2xl shadow-2xl p-4 cursor-default">
                                <img src="{{ asset('storage/' . $category->image) }}"
                                     class="max-h-[75vh] max-w-full object-contain rounded-xl">
                            </div>
                        </div>
                    @else
                        {{-- Sin imagen: mostramos un icono genérico de etiqueta --}}
                        <div class="bg-gold-100 dark:bg-gold-900/30 rounded-xl p-4 shrink-0">
                            <svg class="w-8 h-8 text-gold-600 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- NOMBRE + BADGE DE PRODUCTOS --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gold-600 dark:text-gold-400">Categoría</p>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $category->name }}</h1>

                        {{--
                            BADGE INTERACTIVO DE TOTAL DE PRODUCTOS
                            $totalProductCount viene del controlador: suma productos de esta
                            categoría y todos sus descendientes.

                            Si hay productos → es un enlace que lleva a /products con el filtro
                            pre-aplicado. La URL se construye así:
                              route('products.index') → "/products"
                              http_build_query(['path' => $categoryPath]) → "path[]=1&path[]=5"
                              Resultado: "/products?path[]=1&path[]=5"
                            El componente Livewire ProductList lee esos query params gracias
                            al atributo #[Url] y activa el filtro automáticamente.

                            Si no hay productos → badge gris sin link (no tiene sentido ir a ver 0 productos).
                        --}}
                        <div class="flex items-center gap-4 mt-2">
                            @if($totalProductCount > 0)
                                <a href="{{ route('products.index') . '?' . http_build_query(['path' => $categoryPath]) }}"
                                   class="inline-flex items-center gap-1.5 bg-gold-50 dark:bg-gold-900/30 text-gold-700 dark:text-gold-300 text-sm font-semibold px-3 py-1 rounded-full hover:bg-gold-100 dark:hover:bg-gold-800/40 transition"
                                   wire:navigate>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                    </svg>
                                    {{ $totalProductCount }} {{ $totalProductCount === 1 ? 'producto en total' : 'productos en total' }} →
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-sm px-3 py-1 rounded-full">
                                    0 productos
                                </span>
                            @endif

                            {{--
                                Solo mostramos "(X directos)" cuando:
                                1. La categoría tiene subcategorías (si no, total = directos siempre)
                                2. Los directos son más de 0 (si son 0, no aporta información)
                                3. Los directos son distintos al total (si son iguales, es redundante)
                                Ejemplo útil: MOTOR tiene 12 total pero 3 son directos (los otros 9 son de subcategorías)
                            --}}
                            @if($category->children->isNotEmpty() && $directProductCount > 0 && $directProductCount !== $totalProductCount)
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    ({{ $directProductCount }} directos)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <p class="mt-6 text-xs text-gray-400 dark:text-gray-500">
                    Añadida el {{ $category->created_at->format('d/m/Y') }}
                </p>

                {{--
                    BOTONES DE ACCIÓN (Editar / Eliminar)
                    x-data="{ open: false }" inicia el estado del modal de confirmación de borrado.
                --}}
                <div class="mt-8 flex items-center gap-3 border-t border-gray-100 dark:border-gray-700 pt-6"
                     x-data="{ open: false }">

                    {{-- Botón Editar: navega al formulario de edición --}}
                    <a href="{{ route('categories.edit', $category) }}"
                       class="flex items-center gap-2 bg-gold-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-gold-700 transition shadow-md hover:shadow-xl"
                       wire:navigate.hover>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>

                    {{-- Botón Eliminar: no elimina directamente, abre el modal de confirmación --}}
                    <button @click="open = true"
                            class="flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-red-700 transition shadow-md hover:shadow-xl">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>

                    {{--
                        MODAL DE CONFIRMACIÓN DE BORRADO
                        Se muestra sobre todo el contenido (fixed inset-0 z-50).
                        El fondo semitransparente (bg-gray-900/60) bloquea visualmente el resto.
                        @click.outside="open = false" cierra el modal al hacer click fuera del cuadro.
                        La acción de borrado usa un formulario POST con @method('DELETE')
                        porque los navegadores solo soportan GET y POST nativamente; Laravel
                        detecta el campo _method y enruta al método destroy del controlador.
                    --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
                         @keydown.escape.window="open = false">

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.outside="open = false"
                             class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">

                            {{-- Icono de advertencia --}}
                            <div class="flex justify-center mb-5">
                                <div class="bg-red-100 dark:bg-red-900/50 rounded-full p-4">
                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Texto explicativo del impacto del borrado --}}
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">
                                ¿Eliminar categoría?
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                Vas a eliminar <span class="font-semibold text-gray-700 dark:text-gray-200">«{{ $category->name }}»</span>
                                y <span class="font-semibold text-red-600 dark:text-red-400">todos los productos asociados</span>.
                                Esta acción no se puede deshacer.
                            </p>

                            {{-- Acciones: cancelar o confirmar el borrado --}}
                            <div class="mt-6 flex gap-3">
                                <button @click="open = false"
                                        class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                    Cancelar
                                </button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-1">
                                    @csrf {{-- Token CSRF: protege contra ataques de falsificación de petición --}}
                                    @method('DELETE') {{-- Laravel interpreta esto como método HTTP DELETE --}}
                                    <button type="submit"
                                            class="w-full px-4 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium transition shadow-md">
                                        Sí, eliminar
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            {{--
                SECCIÓN DE SUBCATEGORÍAS
                Solo se renderiza si esta categoría tiene hijos directos.
                $category->children está cargado desde el controlador con ->load(['children.allChildren']).
            --}}
            @if($category->children->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                        Subcategorías <span class="text-sm font-normal text-gray-400 dark:text-gray-500">({{ $category->children->count() }})</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($category->children as $child)

                            {{--
                                TARJETA DE SUBCATEGORÍA
                                Está dividida en dos zonas clicables con semántica diferente:
                                1. Parte superior (link) → va al detalle de la subcategoría
                                2. Franja inferior (link) → va a los productos con filtro aplicado
                                No se puede usar <a> dentro de <a> (HTML inválido), por eso
                                la tarjeta es un <div> y cada zona es un <a> independiente.
                            --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-gold-900/30 hover:border-gold-300 dark:hover:border-gold-600 transition-all duration-300">

                                {{-- LINK PRINCIPAL → detalle de la subcategoría --}}
                                <a href="{{ route('categories.show', $child) }}"
                                   class="p-5 flex items-center gap-3 group"
                                   wire:navigate.hover>
                                    @if($child->image)
                                        <img src="{{ asset('storage/' . $child->image) }}"
                                             class="w-10 h-10 rounded-xl object-cover shrink-0 border border-gray-200 dark:border-gray-600">
                                    @else
                                        <div class="bg-gold-100 dark:bg-gold-900/30 rounded-xl p-2.5 shrink-0">
                                            <svg class="w-4 h-4 text-gold-600 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="font-semibold text-gray-900 dark:text-white truncate flex-1">{{ $child->name }}</span>
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                                {{--
                                    FRANJA INFERIOR → link directo a productos con filtro
                                    $childProductCounts[$child->id] viene del controlador.
                                    Solo mostramos el link si tiene productos (si no, mostramos "Sin productos").

                                    La URL generada es: /products?path[]=1&path[]=5
                                    Que el componente Livewire ProductList interpreta como
                                    "filtrar por MOTOR > COCHE".

                                    Nota: usamos wire:navigate (sin .hover) porque queremos
                                    que la navegación sea inmediata al hacer click, no al hover.
                                --}}
                                @if(($childProductCounts[$child->id] ?? 0) > 0)
                                    <a href="{{ route('products.index') . '?' . http_build_query(['path' => $childPaths[$child->id]]) }}"
                                       class="flex items-center gap-1.5 px-5 py-2.5 border-t border-gray-100 dark:border-gray-700 text-xs font-medium text-gold-600 dark:text-gold-400 hover:bg-gold-50 dark:hover:bg-gold-900/20 transition"
                                       wire:navigate>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                        </svg>
                                        Ver {{ $childProductCounts[$child->id] }} {{ $childProductCounts[$child->id] === 1 ? 'producto' : 'productos' }} →
                                    </a>
                                @else
                                    <div class="px-5 py-2.5 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
                                        Sin productos
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
