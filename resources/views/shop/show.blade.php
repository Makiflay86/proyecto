<x-store-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-400 dark:text-gray-500 mb-6 flex-wrap">
            <a href="{{ route('store.index') }}" class="hover:text-gold-500 transition">Inicio</a>
            @if($product->category)
                <span>/</span>
                <a href="{{ route('store.index', ['categoria' => $product->category->id]) }}"
                   class="hover:text-gold-500 transition">{{ $product->category->name }}</a>
            @endif
            <span>/</span>
            <span class="text-gray-600 dark:text-gray-300 truncate max-w-xs">{{ $product->nombre }}</span>
        </nav>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

            {{-- Galería de imágenes --}}
            @if($product->images->isNotEmpty())
                <div x-data="{
                        open: false,
                        current: 0,
                        images: {{ Js::from($product->images->pluck('path')) }},
                        show(index) { this.current = index; this.open = true; },
                        prev() { this.current = (this.current - 1 + this.images.length) % this.images.length; },
                        next() { this.current = (this.current + 1) % this.images.length; },
                     }"
                     @keydown.escape.window="open = false"
                     @keydown.arrow-left.window="open && prev()"
                     @keydown.arrow-right.window="open && next()">

                    <div class="flex gap-2 p-4 bg-gray-50 dark:bg-gray-700 overflow-x-auto">
                        @foreach($product->images as $i => $image)
                            <button type="button" @click="show({{ $i }})" class="shrink-0 group relative">
                                <img src="{{ asset('storage/' . $image->path) }}"
                                     class="h-56 w-auto rounded-xl object-cover transition group-hover:brightness-90 cursor-zoom-in">
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-xl">
                                    <svg class="w-7 h-7 text-white drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                                    </svg>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    {{-- Modal lightbox --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="open = false"
                         class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-gray-900/80 cursor-zoom-out">

                        <div @click.stop class="bg-gray-100 dark:bg-gray-700 rounded-2xl shadow-2xl p-4 cursor-default max-h-[80vh] flex items-center">
                            <template x-for="(path, index) in images" :key="index">
                                <img x-show="current === index"
                                     :src="'/storage/' + path"
                                     class="max-h-[72vh] max-w-[80vw] object-contain rounded-xl">
                            </template>
                        </div>

                        @if($product->images->count() > 1)
                            <button @click.stop="prev()"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white rounded-full p-3 transition backdrop-blur-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button @click.stop="next()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white rounded-full p-3 transition backdrop-blur-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <div @click.stop class="absolute bottom-5 flex gap-2 cursor-default">
                                <template x-for="(_, index) in images" :key="index">
                                    <button @click.stop="current = index"
                                            :class="current === index ? 'bg-white w-5' : 'bg-white/50 w-2'"
                                            class="h-2 rounded-full transition-all duration-300">
                                    </button>
                                </template>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-300 dark:text-gray-600">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            {{-- Detalle del producto --}}
            <div class="p-8">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        @if($product->category)
                            <span class="text-xs font-semibold uppercase tracking-wider text-gold-600 dark:text-gold-400">
                                {{ $product->category->breadcrumb }}
                            </span>
                        @endif
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $product->nombre }}</h1>
                        @if($product->user)
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                                Publicado por <a href="{{ route('users.profile', $product->user) }}" class="font-medium text-gray-600 dark:text-gray-300 hover:text-gold-500 dark:hover:text-gold-400 transition">{{ $product->user->name }}</a>
                            </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        @livewire('shop.toggle-like', ['productId' => $product->id], key('show-'.$product->id))
                        <span class="bg-gold-50 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400 px-5 py-2 rounded-xl text-2xl font-bold">
                            {{ number_format($product->precio, 2, ',', '.') }} €
                        </span>
                    </div>
                </div>

                <p class="mt-6 text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $product->descripcion }}</p>

                @auth
                    @if(auth()->id() === $product->user_id)
                        {{-- Dueño --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Gestión del vendedor</p>
                                <div class="flex items-center gap-3" x-data="{ openDelete: false }">
                                    <a href="{{ route('publish.edit', $product) }}"
                                       class="inline-flex items-center gap-1.5 text-sm font-medium text-gold-600 dark:text-gold-400 hover:text-gold-700 dark:hover:text-gold-300 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Editar
                                    </a>
                                    <button @click="openDelete = true"
                                            class="inline-flex items-center gap-1.5 text-sm font-medium text-red-500 hover:text-red-700 dark:hover:text-red-400 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
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
                            @livewire('shop.manage-product-status', ['product' => $product])
                        </div>
                    @else
                        {{-- Otros usuarios autenticados --}}
                        <div class="mt-6 flex items-center gap-4 flex-wrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Estado:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $product->isSold()
                                        ? 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400'
                                        : ($product->isReserved()
                                            ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400'
                                            : 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300') }}">
                                    {{ ucfirst($product->estado) }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                Publicado el {{ $product->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                            @if($product->isSold())
                                <div class="flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-5 py-3 rounded-xl font-semibold text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Este producto ya ha sido vendido
                                </div>
                            @elseif($product->isReserved())
                                <div class="flex flex-wrap items-center gap-4">
                                    <div class="flex items-center gap-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400 px-5 py-3 rounded-xl font-semibold text-sm h-[52px]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Este producto está reservado
                                    </div>
                                    <a href="{{ route('chat.show', $product) }}"
                                       class="inline-flex items-center gap-2 bg-gold-600 hover:bg-gold-700 text-white font-bold px-8 py-3 rounded-xl shadow-md hover:shadow-lg transition-all h-[52px]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                        Preguntar si sigue disponible
                                    </a>
                                </div>
                            @else
                                <a href="{{ route('chat.show', $product) }}"
                                   class="inline-flex items-center gap-2 bg-gold-600 hover:bg-gold-700 text-white font-bold px-8 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    Contactar con el vendedor
                                </a>
                            @endif
                        </div>
                    @endif
                @else
                    {{-- Visitantes --}}
                    <div class="mt-6 flex items-center gap-4 flex-wrap">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Estado:</span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $product->isSold()
                                    ? 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400'
                                    : ($product->isReserved()
                                        ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400'
                                        : 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300') }}">
                                {{ ucfirst($product->estado) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center gap-4 flex-wrap">
                        <a href="{{ route('login') }}"
                           class="bg-gold-600 hover:bg-gold-700 text-white font-bold px-8 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
                            Inicia sesión para contactar
                        </a>
                        <span class="text-sm text-gray-500 dark:text-gray-400">¿No tienes cuenta?
                            <a href="{{ route('register') }}" class="text-gold-500 hover:text-gold-400 font-semibold transition">Regístrate gratis</a>
                        </span>
                    </div>
                @endauth

            </div>
        </div>
    </div>
</x-store-layout>
