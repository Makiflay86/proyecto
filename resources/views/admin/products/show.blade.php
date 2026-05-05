<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ $product->nombre }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('products.index') }}" class="inline-block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm mb-6 ps-4" wire:navigate.hover>
                ← Volver a productos
            </a>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">

                {{-- Imágenes --}}
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

                        {{-- Miniaturas --}}
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

                        {{-- Modal --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="open = false"
                             class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-gray-900/80 cursor-zoom-out">

                            {{-- Imagen --}}
                            <div @click.stop class="bg-gray-100 dark:bg-gray-700 rounded-2xl shadow-2xl p-4 cursor-default max-h-[80vh] flex items-center">
                                <template x-for="(path, index) in images" :key="index">
                                    <img x-show="current === index"
                                         :src="'/storage/' + path"
                                         class="max-h-[72vh] max-w-[80vw] object-contain rounded-xl">
                                </template>
                            </div>

                            {{-- Flechas (solo si hay más de una imagen) --}}
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

                                {{-- Indicador de posición --}}
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
                    <div class="h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 dark:text-gray-500">
                        Sin imagen
                    </div>
                @endif

                {{-- Detalle --}}
                <div class="p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">{{ $product->category?->breadcrumb }}</span>
                            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $product->nombre }}</h1>
                        </div>
                        <span class="text-2xl font-bold text-gray-800 dark:text-gray-100 shrink-0">
                            {{ number_format($product->precio, 2, ',', '.') }}€
                        </span>
                    </div>

                    <p class="mt-6 text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $product->descripcion }}</p>

                    <div class="mt-6 flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Estado:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $product->isSold()
                                ? 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400'
                                : ($product->isReserved()
                                    ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400'
                                    : ($product->estado === 'activo'
                                        ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300'
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400')) }}">
                            {{ ucfirst($product->estado) }}
                        </span>
                    </div>

                    <p class="mt-4 text-xs text-gray-400 dark:text-gray-500">Añadido el {{ $product->created_at->format('d/m/Y') }}</p>

                    {{-- Botones de acción --}}
                    <div class="mt-8 flex items-center gap-3 border-t border-gray-100 dark:border-gray-700 pt-6"
                         x-data="{ open: false }">

                        {{-- Editar --}}
                        <a href="{{ route('products.edit', $product) }}"
                           class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition shadow-md hover:shadow-xl"
                           wire:navigate.hover>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>

                        {{-- Botón que abre el modal --}}
                        <button @click="open = true"
                                class="flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-red-700 transition shadow-md hover:shadow-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>

                        {{-- Modal de confirmación --}}
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

                                {{-- Icono --}}
                                <div class="flex justify-center mb-5">
                                    <div class="bg-red-100 dark:bg-red-900/50 rounded-full p-4">
                                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Texto --}}
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">
                                    ¿Eliminar producto?
                                </h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                    Vas a eliminar <span class="font-semibold text-gray-700 dark:text-gray-200">«{{ $product->nombre }}»</span>
                                    y todas sus imágenes. Esta acción no se puede deshacer.
                                </p>

                                {{-- Acciones --}}
                                <div class="mt-6 flex gap-3">
                                    <button @click="open = false"
                                            class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                        Cancelar
                                    </button>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
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

            </div>
        </div>
    </div>
</x-app-layout>
