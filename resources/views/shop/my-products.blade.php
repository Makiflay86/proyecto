<x-store-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                Mis productos
                <span class="ml-2 text-sm font-normal text-gray-400 dark:text-gray-500">({{ $products->count() }})</span>
            </h1>
            <a href="{{ route('publish.create') }}"
               class="hidden sm:inline-flex items-center gap-1.5 bg-gold-500 hover:bg-gold-600 text-white font-semibold px-4 py-2.5 rounded-full text-sm transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Añadir producto
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            @if($products->isEmpty())
                <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">
                    Todavía no has publicado ningún producto.
                </p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl border border-gray-100 dark:border-gray-600 overflow-hidden hover:shadow-lg transition-all duration-300 group relative flex flex-col"
                             x-data="{ openDelete: false }">

                            {{-- Badge estado --}}
                            @if($product->isSold())
                                <div class="absolute top-3 left-3 z-10 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow">
                                    Vendido
                                </div>
                            @elseif($product->isReserved())
                                <div class="absolute top-3 left-3 z-10 bg-amber-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow">
                                    Reservado
                                </div>
                            @endif

                            <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-600 {{ $product->isSold() || $product->isReserved() ? 'opacity-50' : '' }}">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                         alt="{{ $product->nombre }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-500">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $product->nombre }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $product->descripcion }}</p>
                                <div class="mt-3 flex-1 flex flex-col justify-end">
                                    <span class="font-bold text-gray-800 dark:text-gray-200 mb-3">{{ number_format($product->precio, 2, ',', '.') }} €</span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('store.show', $product) }}"
                                           class="flex-1 text-center text-sm font-medium text-gold-600 dark:text-gold-400 hover:text-gold-800 dark:hover:text-gold-300 bg-gold-50 dark:bg-gold-900/20 hover:bg-gold-100 dark:hover:bg-gold-900/40 px-3 py-2 rounded-lg transition">
                                            Ver
                                        </a>
                                        @unless($product->isSold())
                                        <a href="{{ route('publish.edit', $product) }}"
                                           class="flex-1 text-center text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 px-3 py-2 rounded-lg transition">
                                            Editar
                                        </a>
                                        @endunless
                                        <button @click="openDelete = true"
                                                class="flex-1 text-center text-sm font-medium text-red-500 dark:text-red-400 hover:text-red-700 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-2 rounded-lg transition">
                                            Eliminar
                                        </button>

                                        {{-- Modal confirmar eliminación --}}
                                        <div x-show="openDelete"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
                                             @keydown.escape.window="openDelete = false">
                                            <div x-show="openDelete"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 @click.outside="openDelete = false"
                                                 class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">
                                                <div class="flex justify-center mb-5">
                                                    <div class="bg-red-100 dark:bg-red-900/50 rounded-full p-4">
                                                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">¿Eliminar producto?</h3>
                                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                                    Vas a eliminar
                                                    <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $product->nombre }}</span>.
                                                    Esta acción no se puede deshacer.
                                                </p>
                                                <div class="mt-6 flex gap-3">
                                                    <button @click="openDelete = false"
                                                            class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                                        Cancelar
                                                    </button>
                                                    <form action="{{ route('publish.destroy', $product) }}" method="POST" class="flex-1">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                                class="w-full px-4 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium transition shadow-md">
                                                            Sí
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-store-layout>
