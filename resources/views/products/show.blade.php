<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Botón volver --}}
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-800 text-sm mb-6" wire:navigate.hover>
                <i class="bi bi-arrow-left"></i> Volver a productos
            </a>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Imágenes --}}
                @if($product->images->isNotEmpty())
                    <div class="flex gap-2 p-4 bg-gray-50 overflow-x-auto">
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}"
                                 class="h-56 w-auto rounded-xl object-cover shrink-0">
                        @endforeach
                    </div>
                @else
                    <div class="h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                        <i class="bi bi-image text-4xl"></i>
                    </div>
                @endif

                {{-- Detalle --}}
                <div class="p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600">{{ $product->categoria }}</span>
                            <h1 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $product->nombre }}</h1>
                        </div>
                        <span class="text-2xl font-bold text-gray-800 shrink-0">
                            {{ number_format($product->precio, 2, ',', '.') }}€
                        </span>
                    </div>

                    <p class="mt-6 text-gray-600 leading-relaxed">{{ $product->descripcion }}</p>

                    <div class="mt-6 flex items-center gap-2">
                        <span class="text-sm text-gray-500">Estado:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $product->estado === 'activo' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($product->estado) }}
                        </span>
                    </div>

                    <p class="mt-4 text-xs text-gray-400">Añadido el {{ $product->created_at->format('d/m/Y') }}</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
