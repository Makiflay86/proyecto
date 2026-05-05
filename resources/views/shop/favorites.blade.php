<x-store-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center gap-3 mb-8">
            <div class="bg-red-100 dark:bg-red-900/30 rounded-full p-2.5">
                <svg class="w-5 h-5 text-red-500 dark:text-red-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">Mis favoritos</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $products->count() }} {{ $products->count() === 1 ? 'producto guardado' : 'productos guardados' }}</p>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-full p-6">
                        <svg class="w-12 h-12 text-red-300 dark:text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Aún no tienes favoritos</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
                    Pulsa el corazón en cualquier producto para guardarlo aquí.
                </p>
                <a href="{{ route('store.index') }}"
                   class="inline-flex items-center gap-2 bg-gold-500 hover:bg-gold-600 text-gray-900 dark:text-white font-semibold px-8 py-3 rounded-full shadow-md hover:shadow-lg transition-all">
                    Explorar productos
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-gold-900/30 transition-all duration-300 relative">

                        @if($product->isSold())
                            <div class="absolute top-2 left-2 z-10">
                                <span class="bg-red-600 text-white text-[10px] font-bold uppercase px-2 py-0.5 rounded shadow-sm">Vendido</span>
                            </div>
                        @elseif($product->isReserved())
                            <div class="absolute top-2 left-2 z-10">
                                <span class="bg-amber-500 text-white text-[10px] font-bold uppercase px-2 py-0.5 rounded shadow-sm">Reservado</span>
                            </div>
                        @endif

                        <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-700 {{ $product->isSold() || $product->isReserved() ? 'opacity-75' : '' }}">
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
                                @livewire('shop.toggle-like', ['productId' => $product->id], key('fav-'.$product->id))
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
        @endif

    </div>
</x-store-layout>
