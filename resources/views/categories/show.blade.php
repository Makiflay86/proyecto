<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('categories.index') }}"
               class="inline-block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm mb-6 ps-4"
               wire:navigate.hover>
                ← Volver a categorías
            </a>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 transition-colors duration-300">

                <div class="flex items-center gap-4">
                    {{-- Imagen si existe, icono por defecto si no --}}
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                             class="w-20 h-20 rounded-xl object-cover border border-gray-200 dark:border-gray-600 shadow-sm shrink-0">
                    @else
                        <div class="bg-indigo-100 dark:bg-indigo-900 rounded-xl p-4 shrink-0">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Categoría</p>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $category->name }}</h1>
                    </div>
                </div>

                <p class="mt-6 text-xs text-gray-400 dark:text-gray-500">
                    Añadida el {{ $category->created_at->format('d/m/Y') }}
                </p>

                {{-- Botones de acción --}}
                <div class="mt-8 flex items-center gap-3 border-t border-gray-100 dark:border-gray-700 pt-6"
                     x-data="{ open: false }">

                    {{-- Editar --}}
                    <a href="{{ route('categories.edit', $category) }}"
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
                                ¿Eliminar categoría?
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                Vas a eliminar <span class="font-semibold text-gray-700 dark:text-gray-200">«{{ $category->name }}»</span>
                                y <span class="font-semibold text-red-600 dark:text-red-400">todos los productos asociados</span>.
                                Esta acción no se puede deshacer.
                            </p>

                            {{-- Acciones --}}
                            <div class="mt-6 flex gap-3">
                                <button @click="open = false"
                                        class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                    Cancelar
                                </button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-1">
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
</x-app-layout>
