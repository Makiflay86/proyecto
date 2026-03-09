<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ $product->nombre }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('products.index') }}" class="inline-block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm mb-6" wire:navigate.hover>
                ← Volver a productos
            </a>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">

                {{-- Imágenes --}}
                @if($product->images->isNotEmpty())
                    <div class="flex gap-2 p-4 bg-gray-50 dark:bg-gray-700 overflow-x-auto">
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}"
                                 class="h-56 w-auto rounded-xl object-cover shrink-0">
                        @endforeach
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
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">{{ $product->categoria }}</span>
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
                            {{ $product->estado === 'activo'
                                ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300'
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                            {{ ucfirst($product->estado) }}
                        </span>
                    </div>

                    <p class="mt-4 text-xs text-gray-400 dark:text-gray-500">Añadido el {{ $product->created_at->format('d/m/Y') }}</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
