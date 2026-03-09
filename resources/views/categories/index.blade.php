<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Categorías') }}
            </h2>
            <a href="{{ route('categories.create') }}"
               class="bg-indigo-600 text-white px-6 py-2 rounded-full shadow-lg hover:bg-indigo-700 transition duration-200"
               wire:navigate.hover>
                + Crear Categoría
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green green-700 px-4 py-3 rounded-full relative mb-4 alert-fade">
                    {{ session('success') }}
                </div>
            @endif

            @if($categories->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center transition-colors duration-300">
                    <p class="text-gray-400 dark:text-gray-500 text-sm">No hay categorías registradas aún.</p>
                    <a href="{{ route('categories.create') }}"
                       class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2 rounded-full hover:bg-indigo-700 transition duration-200 text-sm"
                       wire:navigate.hover>
                        Crear la primera categoría
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($categories as $category)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-indigo-900/50 transition-all duration-300">
                            <div class="p-6 flex flex-col gap-4">

                                {{-- Icono + nombre --}}
                                <div class="flex items-center gap-3">
                                    {{-- Imagen si existe, icono por defecto si no --}}
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                             class="w-12 h-12 rounded-xl object-cover shrink-0 border border-gray-200 dark:border-gray-600">
                                    @else
                                        <div class="bg-indigo-100 dark:bg-indigo-900 rounded-xl p-3 shrink-0">
                                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">
                                        {{ $category->name }}
                                    </h3>
                                </div>

                                {{-- Fecha --}}
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    Añadida el {{ $category->created_at->format('d/m/Y') }}
                                </p>

                                {{-- Enlace ver detalle --}}
                                <div class="flex justify-end">
                                    <a href="{{ route('categories.show', $category) }}"
                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm"
                                       wire:navigate.hover>
                                        Ver detalle →
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
