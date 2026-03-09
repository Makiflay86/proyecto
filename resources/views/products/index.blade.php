<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Productos') }}
            </h2>
            <a href="{{ route('products.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-full shadow-lg hover:bg-indigo-700 transition duration-200" wire:navigate.hover>
                + Crear Producto
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-full relative mb-4 alert-fade">
                    {{ session('success') }}
                </div>
            @endif

            @if($products->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center transition-colors duration-300">
                    <div class="flex justify-center mb-6">
                        <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-6">
                            <svg class="w-12 h-12 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </div>
                    </div>
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
                </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-indigo-900/50 transition-all duration-300">
                        <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">Sin imagen</div>
                            @endif
                        </div>

                        <div class="p-5">
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">{{ $product->categoria }}</span>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $product->nombre }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm line-clamp-2">{{ $product->descripcion }}</p>

                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ number_format($product->precio, 2, ",", ".") }}€</span>
                                <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm" wire:navigate.hover>Ver detalles →</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>