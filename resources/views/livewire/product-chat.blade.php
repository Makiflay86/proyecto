<div class="flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden"
     style="height: 32rem;">

    {{-- Cabecera: imagen del producto + nombre + contraparte --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 shrink-0">
        <a href="{{ route('store.show', $product) }}"
           class="flex items-center gap-3 min-w-0 flex-1 hover:opacity-80 transition">
            <div class="rounded-xl overflow-hidden bg-gray-200 dark:bg-gray-600 shrink-0"
                 style="width:2.75rem;height:2.75rem;min-width:2.75rem;min-height:2.75rem">
                @if($product->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                         class="w-full h-full object-cover block"
                         alt="{{ $product->nombre }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="min-w-0">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ $product->nombre }}</h3>
                @if(Auth::user()->is_admin)
                    <p class="text-xs text-gray-500 dark:text-gray-400">Conversación con <span class="font-medium">{{ $threadUser->name }}</span></p>
                @else
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Vendedor · <span class="font-medium">{{ $product->user?->name ?? 'Venalia' }}</span>
                    </p>
                @endif
            </div>
        </a>
    </div>

    {{-- Área de mensajes con auto-scroll y polling cada 3s --}}
    <div wire:poll.3000ms
         x-data="{
             init() {
                 this.$nextTick(() => { this.$el.scrollTop = this.$el.scrollHeight; });
                 new MutationObserver(() => { this.$el.scrollTop = this.$el.scrollHeight; })
                     .observe(this.$el, { childList: true, subtree: true });
             }
         }"
         x-init="init()"
         class="flex-1 overflow-y-auto px-5 py-4">

        @forelse($messages as $message)
            @php $isMe = $message->sender_id === Auth::id(); @endphp
            <div class="flex mb-3 {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-sm px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                    {{ $isMe
                        ? 'bg-gold-500 text-white rounded-br-sm'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-bl-sm' }}">
                    <p class="whitespace-pre-wrap break-words">{{ $message->body }}</p>
                    <p class="text-xs mt-1 {{ $isMe ? 'text-yellow-100' : 'text-gray-400 dark:text-gray-500' }}">
                        {{ $message->created_at->format('H:i') }}
                        @if($isMe && $message->read_at)
                            · leído
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full">
                <p class="text-gray-400 dark:text-gray-500 text-sm">Escribe el primer mensaje sobre este producto.</p>
            </div>
        @endforelse
    </div>

    {{-- Input de envío --}}
    <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 shrink-0">
        <form wire:submit.prevent="sendMessage" class="flex gap-3">
            <input type="text"
                   wire:model="body"
                   placeholder="Escribe un mensaje…"
                   autocomplete="off"
                   class="flex-1 rounded-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
            <button type="submit"
                    class="shrink-0 bg-gold-500 hover:bg-gold-600 text-white font-semibold rounded-full px-5 py-2 text-sm transition shadow-sm hover:shadow-md">
                Enviar
            </button>
        </form>
    </div>
</div>
