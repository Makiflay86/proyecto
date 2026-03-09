<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Editar producto
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('products.show', $product) }}"
               class="inline-block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm mb-6 ps-4"
               wire:navigate.hover>
                ← Volver al detalle
            </a>

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

                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $product->nombre) }}"
                               class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                        <textarea name="descripcion" rows="4"
                                  class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">{{ old('descripcion', $product->descripcion) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="precio" value="{{ old('precio', $product->precio) }}"
                                       class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-3 pr-8 transition" required>
                                <span class="absolute right-3 top-2 text-gray-500 dark:text-gray-400">€</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                            <select name="category_id"
                                    class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                <option value="" disabled>Selecciona una categoría</option>
                                @foreach($categoryOptions as $opt)
                                    <option value="{{ $opt['id'] }}" {{ old('category_id', $product->category_id) == $opt['id'] ? 'selected' : '' }}>
                                        {{ $opt['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                            <select name="estado"
                                    class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                                <option value="activo"   {{ old('estado', $product->estado) === 'activo'   ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $product->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    {{-- Imágenes actuales con botón X para marcarlas para eliminar --}}
                    @if($product->images->isNotEmpty())
                        <div x-data="{ toDelete: [] }">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imágenes actuales</label>
                            <div class="flex gap-3 flex-wrap">
                                @foreach($product->images as $image)
                                    <div x-data="{ id: {{ $image->id }} }" class="relative group"
                                         x-show="!toDelete.includes(id)">
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                             class="h-32 w-32 rounded-xl object-cover border border-gray-200 dark:border-gray-600">
                                        {{-- Al hacer clic marca el ID para borrar y envía un input hidden --}}
                                        <button type="button"
                                                @click="toDelete.push(id)"
                                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full text-xs flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity">
                                            ✕
                                        </button>
                                    </div>
                                    {{-- Input hidden que solo se envía si el ID está en toDelete --}}
                                    <template x-if="toDelete.includes({{ $image->id }})">
                                        <input type="hidden" name="delete_images[]" value="{{ $image->id }}">
                                    </template>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Preview de imágenes nuevas a añadir --}}
                    <div x-data="{
                            previews: [],
                            accum: new DataTransfer(),
                            handleFiles(event) {
                                Array.from(event.target.files).forEach(file => {
                                    this.accum.items.add(file);
                                    const reader = new FileReader();
                                    reader.onload = e => this.previews.push(e.target.result);
                                    reader.readAsDataURL(file);
                                });
                                this.$refs.fileInput.files = this.accum.files;
                            },
                            remove(index) {
                                this.previews.splice(index, 1);
                                const newDt = new DataTransfer();
                                Array.from(this.accum.files)
                                    .filter((_, i) => i !== index)
                                    .forEach(f => newDt.items.add(f));
                                this.accum = newDt;
                                this.$refs.fileInput.files = this.accum.files;
                            }
                        }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Añadir imágenes</label>
                        {{-- Input oculto: evita el texto nativo "N archivos" que se desincroniza al manipular el FileList --}}
                        <input x-ref="fileInput" type="file" name="images[]" multiple
                               @change="handleFiles($event)"
                               class="hidden">
                        <button type="button" @click="$refs.fileInput.click()"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Seleccionar imágenes
                        </button>

                        {{-- Grid de previews --}}
                        <div x-show="previews.length > 0" class="mt-3 flex flex-wrap gap-3">
                            <template x-for="(src, index) in previews" :key="index">
                                <div class="relative group">
                                    <img :src="src" class="h-32 w-32 object-cover rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                    <button type="button"
                                            @click="remove(index)"
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full text-xs flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity">
                                        ✕
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center pt-4">
                        <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition shadow-md hover:shadow-xl">
                            Guardar cambios
                        </button>
                        <a href="{{ route('products.show', $product) }}"
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
