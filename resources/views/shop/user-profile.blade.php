<x-store-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Navegación --}}
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gold-500 dark:hover:text-gold-400 transition mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>

        {{-- Cabecera del perfil --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center gap-5 flex-wrap">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                     class="w-20 h-20 rounded-full object-cover shrink-0">
            @else
                <div class="w-20 h-20 rounded-full bg-gold-500 flex items-center justify-center text-white text-3xl font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white truncate">{{ $user->name }}</h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Miembro desde {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="text-center shrink-0">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $products->count() }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Productos en venta</p>
            </div>

            @php
                $avg   = $user->averageRating();
                $total = $user->ratingsCount();
            @endphp
            <div class="text-center shrink-0 border-l border-gray-100 dark:border-gray-700 pl-5 ml-1">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-5 h-5 {{ $avg !== null ? 'text-gold-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span class="text-2xl font-bold {{ $avg !== null ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                        {{ $avg !== null ? number_format($avg, 1) : '—' }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $total > 0 ? $total . ' ' . ($total === 1 ? 'valoración' : 'valoraciones') : 'Sin valoraciones' }}
                </p>
            </div>
            @auth
                @if(auth()->id() === $user->id)
                    <a href="{{ route('profile.store') }}"
                       class="inline-flex items-center gap-1.5 bg-gold-500 hover:bg-gold-600 text-white font-semibold px-4 py-2.5 rounded-full text-sm transition shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828A2 2 0 019 17H7v-2a2 2 0 01.586-1.414z"/>
                        </svg>
                        Editar perfil
                    </a>
                @endif
            @endauth
        </div>

        {{-- Productos del usuario --}}
        @if($products->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-5">
                    Productos en venta
                    <span class="ml-2 text-xs font-normal text-gray-400 dark:text-gray-500">({{ $products->count() }})</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl border border-gray-100 dark:border-gray-600 overflow-hidden hover:shadow-lg transition-all duration-300 group relative flex flex-col">

                            {{-- Badge estado --}}
                            @if($product->isReserved())
                                <div class="absolute top-3 left-3 z-10 bg-amber-500 text-white text-[10px] font-bold uppercase px-2 py-0.5 rounded shadow-sm">
                                    Reservado
                                </div>
                            @endif

                            <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-600 {{ $product->isReserved() ? 'opacity-50' : '' }}">
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
                                <div class="mt-3 flex-1 flex items-end justify-between">
                                    <span class="font-bold text-gray-800 dark:text-gray-200 px-3 py-2">{{ number_format($product->precio, 2, ',', '.') }} €</span>
                                    <a href="{{ route('store.show', $product) }}"
                                       class="text-center text-sm font-medium text-gold-600 dark:text-gold-400 hover:text-gold-800 dark:hover:text-gold-300 bg-gold-50 dark:bg-gold-900/20 hover:bg-gold-100 dark:hover:bg-gold-900/40 px-3 py-2 rounded-lg transition">
                                        Ver →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
                <p class="text-gray-400 dark:text-gray-500 text-sm">Este usuario no tiene productos en venta actualmente.</p>
            </div>
        @endif

    </div>
</x-store-layout>
