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
                    </div>
                    <span class="bg-gold-50 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400 px-5 py-2 rounded-xl text-2xl font-bold shrink-0">
                        {{ number_format($product->precio, 2, ',', '.') }} €
                    </span>
                </div>

                <p class="mt-6 text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $product->descripcion }}</p>

                <div class="mt-6 flex items-center gap-4 flex-wrap">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Estado:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $product->estado === 'activo'
                                ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300'
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                            {{ ucfirst($product->estado) }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-400 dark:text-gray-500">
                        Publicado el {{ $product->created_at->format('d/m/Y') }}
                    </span>
                </div>

                {{-- CTA --}}
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    @auth
                        <button class="bg-gold-600 hover:bg-gold-700 text-white font-bold px-8 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
                            Contactar con el vendedor
                        </button>
                    @else
                        <div class="flex items-center gap-4 flex-wrap">
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
    </div>
</x-store-layout>
