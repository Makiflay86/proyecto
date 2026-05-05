<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Editar categoría
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('categories.show', $category) }}"
               class="inline-block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm mb-6 ps-4"
               wire:navigate.hover>
                ← Volver al detalle
            </a>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 transition-colors duration-300">

                <form action="{{ route('categories.update', $category) }}" method="POST"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Nombre de la categoría')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      :value="old('name', $category->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="parent_id" :value="__('Categoría padre')" />
                        <select id="parent_id" name="parent_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                            <option value="">— Sin categoría padre (raíz) —</option>
                            @foreach($categoryOptions as $opt)
                                <option value="{{ $opt['id'] }}" {{ old('parent_id', $category->parent_id) == $opt['id'] ? 'selected' : '' }}>
                                    {{ $opt['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                    </div>

                    {{-- Imagen opcional con preview y opción de eliminar la actual --}}
                    <div x-data="{
                            preview: null,
                            removed: false,
                            handleFile(event) {
                                const file = event.target.files[0];
                                if (!file) return;
                                this.removed = false;
                                const reader = new FileReader();
                                reader.onload = e => this.preview = e.target.result;
                                reader.readAsDataURL(file);
                            },
                            clearNew(input) {
                                this.preview = null;
                                input.value = '';
                            }
                        }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Imagen <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>

                        {{-- Imagen actual --}}
                        @if($category->image)
                            <div x-show="!removed && !preview" class="mb-3 relative inline-block group">
                                <img src="{{ asset('storage/' . $category->image) }}"
                                     class="h-32 w-32 object-cover rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                <button type="button" @click="removed = true"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full text-xs flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity">
                                    ✕
                                </button>
                            </div>
                            {{-- Campo oculto que indica al controller que se quiere eliminar la imagen --}}
                            <input type="hidden" name="remove_image" :value="removed && !preview ? '1' : '0'">
                        @endif

                        {{-- Preview de imagen nueva --}}
                        <div x-show="preview" class="mb-3 relative inline-block group">
                            <img :src="preview" class="h-32 w-32 object-cover rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm">
                            <button type="button" @click="clearNew($refs.imgInput)"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full text-xs flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity">
                                ✕
                            </button>
                        </div>

                        <input x-ref="imgInput" type="file" name="image" accept="image/*"
                               @change="handleFile($event)" class="hidden">
                        <div>
                            <button type="button" @click="$refs.imgInput.click()"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $category->image ? 'Cambiar imagen' : 'Seleccionar imagen' }}
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex items-center pt-2">
                        <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition shadow-md hover:shadow-xl">
                            Guardar cambios
                        </button>
                        <a href="{{ route('categories.show', $category) }}"
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
