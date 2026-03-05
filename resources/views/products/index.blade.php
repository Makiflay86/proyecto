<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-800">Mis Productos</h2>
                <a href="{{ route('products.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-full shadow-lg hover:bg-indigo-700 transition duration-200" wire:navigate.hover>
                    + Crear Producto
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 overflow-hidden bg-gray-100">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">Sin imagen</div>
                            @endif
                        </div>
                        
                        <div class="p-5">
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600">{{ $product->categoria }}</span>
                            <h3 class="text-xl font-bold text-gray-900 mt-1">{{ $product->nombre }}</h3>
                            <p class="text-gray-500 mt-2 text-sm line-clamp-2">{{ $product->descripcion }}</p>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-800">${{ number_format($product->precio, 2) }}</span>
                                <button class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">Ver detalles →</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>