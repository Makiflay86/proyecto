<div class="flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden"
     style="height: 32rem;"
     x-data="{ openSold: false }">

    {{-- Cabecera: imagen del producto + nombre + contraparte --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 shrink-0">
        <div class="flex items-center gap-3 min-w-0 flex-1">
            <a href="{{ route('store.show', $product) }}"
               class="rounded-xl overflow-hidden bg-gray-200 dark:bg-gray-600 shrink-0 hover:opacity-80 transition"
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
            </a>
            <div class="min-w-0">
                <a href="{{ route('store.show', $product) }}" class="font-semibold text-gray-900 dark:text-white text-sm truncate block hover:text-gold-500 transition">{{ $product->nombre }}</a>
                @if($threadUser->id !== Auth::id())
                    <p class="text-xs text-gray-500 dark:text-gray-400">Conversación con <a href="{{ route('users.profile', $threadUser) }}" class="font-medium hover:text-gold-500 transition">{{ $threadUser->name }}</a></p>
                @else
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Vendedor · <a href="{{ route('users.profile', $product->user) }}" class="font-medium hover:text-gold-500 transition">{{ $product->user?->name ?? 'Venalia' }}</a>
                    </p>
                @endif
            </div>
        </div>

        {{-- Botones de estado (solo para el vendedor) --}}
        @if(Auth::id() === $product->user_id && !$product->isSold())
            <div class="flex items-center gap-2 shrink-0">
                @if($product->isReserved())
                    <button wire:click="toggleReserved"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border-2 border-amber-500 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
                        Reservado
                    </button>
                @else
                    <button wire:click="toggleReserved"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-amber-500 hover:bg-amber-600 text-white transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Reservar
                    </button>
                @endif
                <button @click="openSold = true"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-green-600 hover:bg-green-700 text-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Vendido
                </button>
            </div>
        @elseif(Auth::id() === $product->user_id && $product->isSold())
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 shrink-0">
                Vendido
            </span>
        @endif
    </div>

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
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">El producto desaparecerá de la tienda. Esta acción no se puede deshacer fácilmente.</p>
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

    {{-- Modal valoración --}}
    @if($showRatingModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
         x-data="{ hovered: 0, selected: 0 }">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">
            <div class="flex justify-center mb-4">
                <div class="bg-gold-100 dark:bg-gold-900/30 rounded-full p-4">
                    <svg class="w-8 h-8 text-gold-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">¿Cómo fue el trato?</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 text-center">Valora esta transacción del 1 al 5</p>

            {{-- Estrellas --}}
            <div class="flex justify-center gap-2 mt-6">
                @for($i = 1; $i <= 5; $i++)
                <button
                    type="button"
                    @mouseenter="hovered = {{ $i }}"
                    @mouseleave="hovered = 0"
                    @click="selected = {{ $i }}"
                    wire:click="submitRating({{ $i }})"
                    class="transition-transform hover:scale-110 focus:outline-none">
                    <svg class="w-10 h-10 transition-colors"
                         :class="(hovered >= {{ $i }} || selected >= {{ $i }}) ? 'text-gold-400' : 'text-gray-300 dark:text-gray-600'"
                         fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </button>
                @endfor
            </div>

            <button wire:click="skipRating"
                    class="mt-6 w-full text-center text-xs text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition">
                Omitir valoración
            </button>
        </div>
    </div>
    @endif

    {{-- Área de mensajes con auto-scroll y polling cada 3s --}}
    <div wire:poll.3000ms="refreshMessages"
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
            @php
                $isMe = $message->sender_id === Auth::id();
                $msgDate = $message->created_at->startOfDay();
                $prevDate = $loop->first ? null : $messages[$loop->index - 1]->created_at->startOfDay();
                $showDate = $loop->first || ! $msgDate->eq($prevDate);
            @endphp

            @if($showDate)
                <div class="flex items-center gap-3 my-4">
                    <div class="flex-1 h-px bg-gray-200 dark:bg-gray-600"></div>
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-medium shrink-0">
                        @if($message->created_at->isToday())
                            Hoy
                        @elseif($message->created_at->isYesterday())
                            Ayer
                        @else
                            {{ $message->created_at->translatedFormat('j \d\e F \d\e Y') }}
                        @endif
                    </span>
                    <div class="flex-1 h-px bg-gray-200 dark:bg-gray-600"></div>
                </div>
            @endif

            <div class="flex mb-3 {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="relative max-w-xs lg:max-w-sm px-4 pt-2.5 pb-2 rounded-2xl text-sm leading-relaxed
                    {{ $isMe
                        ? 'bg-gold-500 text-white rounded-br-sm'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-bl-sm' }}">
                    <p class="whitespace-pre-wrap break-words">{{ $message->body }}<span class="inline-block w-10 h-3 align-bottom shrink-0"></span></p>
                    <span class="absolute bottom-1.5 right-3 text-xs {{ $isMe ? 'text-yellow-100' : 'text-gray-400 dark:text-gray-500' }}">
                        {{ $message->created_at->format('H:i') }}
                    </span>
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
