<x-store-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">Publicar producto</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rellena los datos y sube fotos para publicar tu producto.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
            <form action="{{ route('publish.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="field-group">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}"
                           class="w-full rounded-xl @error('nombre') border-red-500 @else border border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                    @error('nombre')
                        <p class="field-error text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4"
                              class="w-full rounded-xl @error('descripcion') border-red-500 @else border border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-400 transition">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="field-error text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="field-group">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio</label>
                        <div class="relative">
                            <input id="precio" type="number" step="0.01" min="0" name="precio" value="{{ old('precio') }}"
                                   class="w-full rounded-xl @error('precio') border-red-500 @else border border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700 px-4 py-2.5 pr-8 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                            <span class="absolute right-3 top-2.5 text-gray-400 dark:text-gray-500 text-sm">€</span>
                        </div>
                        @error('precio')
                            <p class="field-error text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                        <select id="category_id" name="category_id"
                                class="w-full rounded-xl @error('category_id') border-red-500 @else border border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                            <option value="" disabled selected>Selecciona una categoría</option>
                            @foreach($categoryOptions as $opt)
                                <option value="{{ $opt['id'] }}" {{ old('category_id') == $opt['id'] ? 'selected' : '' }}>
                                    {{ $opt['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="field-error text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Imágenes con preview --}}
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imágenes</label>

                    <input x-ref="fileInput" type="file" name="images[]" multiple @change="handleFiles($event)" class="hidden">

                    <button type="button" @click="$refs.fileInput.click()"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Seleccionar fotos
                    </button>

                    <div x-show="previews.length > 0" class="mt-3 flex flex-wrap gap-3">
                        <template x-for="(src, index) in previews" :key="index">
                            <div class="relative group">
                                <img :src="src" class="h-24 w-24 object-cover rounded-xl border border-gray-200 dark:border-gray-600">
                                <button type="button" @click="remove(index)"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit"
                            class="bg-gold-500 hover:bg-gold-600 text-white font-semibold px-8 py-2.5 rounded-full text-sm transition shadow-sm hover:shadow-md">
                        Publicar producto
                    </button>
                    <a href="{{ route('profile.store') }}"
                       class="text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

    </div>

    @vite(['resources/js/auth.js', 'resources/js/admin-products.js'])
</x-store-layout>
