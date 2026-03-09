<div class="product-search" @keydown.escape.window="$wire.cerrar()" @click.outside="$wire.cerrar()">

    {{-- Input --}}
    <div class="product-search__input-wrapper">
        <i class="bi bi-search product-search__icon"></i>
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            placeholder="Buscar productos..."
            class="product-search__input"
        >
        @if($query)
            <button wire:click="cerrar" class="product-search__clear">
                <i class="bi bi-x"></i>
            </button>
        @endif
    </div>

    {{-- Resultados --}}
    @if($open && count($results) > 0)
        <div class="product-search__dropdown">
            @foreach($results as $product)
                <a
                    href="{{ route('products.show', $product['id']) }}"
                    wire:click="cerrar"
                    class="product-search__item"
                >
                    <div class="product-search__avatar">
                        {{ strtoupper(substr($product['nombre'], 0, 1)) }}
                    </div>
                    <div class="product-search__item-info">
                        <span class="product-search__item-name">{{ $product['nombre'] }}</span>
                        <span class="product-search__item-meta">{{ ucfirst($product['categoria']) }}</span>
                    </div>
                    <span class="product-search__item-price">
                        {{ number_format($product['precio'], 2, ',', '.') }}€
                    </span>
                </a>
            @endforeach

            <div class="product-search__footer">
                {{ count($results) }} resultado(s) · "{{ $query }}"
            </div>
        </div>
    @endif

    {{-- Sin resultados --}}
    @if($open && count($results) === 0 && strlen($query) >= 2)
        <div class="product-search__dropdown">
            <div class="product-search__empty">
                <i class="bi bi-search product-search__empty-icon"></i>
                <p class="product-search__empty-text">Sin resultados para "{{ $query }}"</p>
            </div>
        </div>
    @endif

</div>
