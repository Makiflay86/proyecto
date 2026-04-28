<x-store-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Navegación --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('chat.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gold-500 dark:hover:text-gold-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Mis mensajes
            </a>
            <a href="{{ route('store.show', $product) }}"
               class="text-sm text-gray-400 dark:text-gray-500 hover:text-gold-500 dark:hover:text-gold-400 transition truncate max-w-xs">
                {{ $product->nombre }}
            </a>
        </div>

        @livewire('product-chat', ['productId' => $product->id, 'threadUserId' => $threadUserId])

    </div>
</x-store-layout>
