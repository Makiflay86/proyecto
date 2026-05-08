<div class="col-span-2 sm:col-span-2 grid grid-cols-2 gap-3" x-data="{ openSold: false }">
    @if($product->isSold())
        <div class="col-span-2 flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-5 py-3 rounded-xl font-semibold text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Este producto ya ha sido vendido
        </div>

    @elseif($product->isReserved())
        <button wire:click="toggleReserved"
                class="w-full inline-flex items-center justify-center gap-2 border-2 border-amber-500 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 font-semibold px-5 py-3 rounded-xl transition">
            Reservado
        </button>
        <button @click="openSold = true"
                class="w-full inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold px-5 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Vendido
        </button>

    @else
        <button wire:click="toggleReserved"
                class="w-full inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold px-5 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Reservar
        </button>
        <button @click="openSold = true"
                class="w-full inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold px-5 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Vendido
        </button>
    @endif

    {{-- Modal confirmar vendido --}}
    <div x-show="openSold"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
         @keydown.escape.window="openSold = false">
        <div x-show="openSold"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.outside="openSold = false"
             class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">
            <div class="flex justify-center mb-5">
                <div class="bg-green-100 dark:bg-green-900/50 rounded-full p-4">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">¿Marcar como vendido?</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                El producto desaparecerá de la tienda. Esta acción no se puede deshacer fácilmente.
            </p>
            <div class="mt-6 flex gap-3">
                <button @click="openSold = false"
                        class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                    Cancelar
                </button>
                <button wire:click="markAsSold" @click="openSold = false"
                        class="flex-1 px-4 py-2.5 rounded-lg bg-green-600 text-white hover:bg-green-700 font-medium transition shadow-md">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
