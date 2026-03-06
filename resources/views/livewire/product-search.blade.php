<div class="relative" x-data @keydown.escape.window="$wire.cerrar()">

    {{-- Input de búsqueda --}}
    <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
        <i class="bi bi-search text-gray-400 text-sm"></i>
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            placeholder="Buscar productos..."
            class="bg-transparent text-sm text-gray-700 placeholder-gray-400 outline-none w-full"
        >
        @if($query)
            <button wire:click="cerrar" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x text-lg leading-none"></i>
            </button>
        @endif
    </div>

    {{-- Desplegable de resultados --}}
    @if($open && count($results) > 0)
        <div class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">

            @foreach($results as $product)
                <a
                    href="{{ route('products.show', $product['id']) }}"
                    wire:click="cerrar"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0"
                >
                    {{-- Inicial del producto --}}
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 font-bold text-sm shrink-0">
                        {{ strtoupper(substr($product['nombre'], 0, 1)) }}
                    </div>

                    <div class="overflow-hidden flex-1">
                        <span class="block text-sm font-semibold text-gray-900 truncate">{{ $product['nombre'] }}</span>
                        <span class="block text-xs text-gray-400 truncate">{{ $product['categoria'] }} · {{ number_format($product['precio'], 2, ',', '.') }}€</span>
                    </div>
                </a>
            @endforeach

            {{-- Pie del desplegable --}}
            <div class="px-4 py-2 bg-gray-50 text-xs text-gray-400 text-center">
                {{ count($results) }} resultado(s) para "{{ $query }}"
            </div>
        </div>
    @endif

    {{-- Sin resultados --}}
    @if($open && count($results) === 0 && strlen($query) >= 2)
        <div class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 px-4 py-6 text-center">
            <i class="bi bi-search text-gray-300 text-2xl"></i>
            <p class="text-sm text-gray-400 mt-1">Sin resultados para "{{ $query }}"</p>
        </div>
    @endif

</div>
