<x-store-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Navegación --}}
        <a href="{{ route('chat.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gold-500 dark:hover:text-gold-400 transition mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Mis mensajes
        </a>

        {{-- Cabecera del perfil --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center gap-5 flex-wrap">
            <div class="w-16 h-16 rounded-full bg-indigo-500 flex items-center justify-center text-white text-2xl font-bold shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white truncate">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Miembro desde {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="flex gap-8 text-center">
                <div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $products->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Productos</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $messageCount }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Conversaciones</p>
                </div>
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('chat.index') }}"
               class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-full text-sm font-medium transition">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                Volver a mensajes
            </a>
        </div>

        {{-- Productos del usuario --}}
        @if($products->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-5">
                    Productos publicados
                    <span class="ml-2 text-xs font-normal text-gray-400 dark:text-gray-500">({{ $products->count() }})</span>
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <a href="{{ route('store.show', $product) }}"
                           class="group block rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all">
                            <div class="h-28 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         alt="{{ $product->nombre }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-2.5">
                                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $product->nombre }}</p>
                                <p class="text-xs text-gold-600 dark:text-gold-400 font-medium mt-0.5">{{ number_format($product->precio, 2, ',', '.') }} €</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
                <p class="text-gray-400 dark:text-gray-500 text-sm">Este usuario no ha publicado ningún producto todavía.</p>
            </div>
        @endif

    </div>
</x-store-layout>
