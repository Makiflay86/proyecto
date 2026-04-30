<div class="flex flex-wrap items-center gap-3">
    @if($product->isSold())
        <div class="flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-5 py-3 rounded-xl font-semibold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Este producto ya ha sido vendido
        </div>
        <button wire:click="reactivate"
                class="text-sm text-gray-500 dark:text-gray-400 hover:text-gold-500 dark:hover:text-gold-400 underline transition">
            Volver a poner en venta
        </button>

    @elseif($product->isReserved())
        <div class="flex items-center gap-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400 px-5 py-3 rounded-xl font-semibold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Este producto está reservado
        </div>
        <button wire:click="toggleReserved"
                class="text-sm text-gray-500 dark:text-gray-400 hover:text-gold-500 dark:hover:text-gold-400 underline transition">
            Quitar reserva
        </button>
        <button wire:click="markAsSold"
                wire:confirm="¿Confirmas que has vendido el producto?"
                class="bg-gray-800 hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
            Confirmar venta
        </button>

    @else
        <div class="flex flex-wrap gap-3">
            <button wire:click="toggleReserved"
                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Reservar
            </button>

            <button wire:click="markAsSold"
                    wire:confirm="¿Marcar este producto como vendido? Desaparecerá de la tienda."
                    class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-500 text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Marcar como vendido
            </button>
        </div>
    @endif
</div>
