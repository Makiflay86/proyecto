<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Nuevo Producto') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/40 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside mt-2 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                        <textarea name="descripcion" rows="4"
                                  class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="precio" value="{{ old('precio') }}"
                                       class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-3 pr-8 transition" required>
                                <span class="absolute right-3 top-2 text-gray-500 dark:text-gray-400">€</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                            <select name="categoria"
                                    class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="electronica" {{ old('categoria') === 'electronica' ? 'selected' : '' }}>Electrónica</option>
                                <option value="hogar"       {{ old('categoria') === 'hogar'       ? 'selected' : '' }}>Hogar</option>
                                <option value="ropa"        {{ old('categoria') === 'ropa'        ? 'selected' : '' }}>Ropa</option>
                                <option value="motor"       {{ old('categoria') === 'motor'       ? 'selected' : '' }}>Motor</option>
                                <option value="libro"       {{ old('categoria') === 'libro'       ? 'selected' : '' }}>Libro</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imágenes</label>
                        <input type="file" name="images[]" multiple
                               class="w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300
                                      hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800 transition">
                    </div>

                    <div class="flex items-center pt-4">
                        <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition shadow-md hover:shadow-xl">
                            Guardar Producto
                        </button>
                        <a href="{{ route('products.index') }}"
                           class="ml-6 text-gray-600 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 font-medium transition"
                           wire:navigate.hover>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
