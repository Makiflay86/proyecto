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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-full relative mb-4 alert-fade">
                    {{ session('success') }}
                </div>
            @endif

            <livewire:admin.product-list />

        </div>
    </div>
</x-app-layout>