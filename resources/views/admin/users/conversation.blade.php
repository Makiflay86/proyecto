<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.show', $user) }}"
               wire:navigate
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight truncate">
                Conversación · {{ $product->nombre ?? 'Producto eliminado' }}
            </h2>
        </div>
    </x-slot>

    <div class="flex flex-col bg-gray-100 dark:bg-gray-900 overflow-hidden" style="height: calc(100vh - 65px)">
        <div class="flex flex-col flex-1 min-h-0 max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 gap-4">

            {{-- Info del hilo --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shrink-0">
                    @if($product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900 dark:text-white truncate">{{ $product->nombre ?? 'Producto eliminado' }}</p>
                    <div class="flex flex-wrap gap-4 mt-1">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Comprador:</span>
                            {{ $user->name }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Vendedor:</span>
                            {{ $product->user?->name ?? '—' }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $messages->count() }} mensajes
                        </span>
                    </div>
                </div>
            </div>

            {{-- Mensajes --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col flex-1 min-h-0 relative"
                 x-data="{
                     atBottom: true,
                     check() { const el = this.$refs.msgs; this.atBottom = el.scrollHeight - el.scrollTop - el.clientHeight < 60; },
                     scrollDown() { this.$refs.msgs.scrollTo({ top: this.$refs.msgs.scrollHeight, behavior: 'smooth' }); }
                 }">

                {{-- Área scrollable --}}
                <div x-ref="msgs"
                     @scroll="check()"
                     x-init="$nextTick(() => { $el.scrollTop = $el.scrollHeight; })"
                     class="flex flex-col flex-1 min-h-0 overflow-y-auto pr-1">

                @forelse($messages as $message)
                    @php
                        $isBuyer  = $message->sender_id === $user->id;
                        $msgDate  = $message->created_at->startOfDay();
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

                    <div class="flex mb-3 {{ $isBuyer ? 'justify-end' : 'justify-start' }}">
                        <div class="flex flex-col {{ $isBuyer ? 'items-end' : 'items-start' }} gap-0.5">
                            <span class="text-xs font-medium px-1 {{ $isBuyer ? 'text-gold-600 dark:text-gold-400' : 'text-blue-600 dark:text-blue-400' }}">
                                {{ $message->sender?->name ?? '—' }}
                                <span class="font-normal text-gray-400">({{ $isBuyer ? 'comprador' : 'vendedor' }})</span>
                            </span>
                            <div class="relative max-w-xs lg:max-w-sm px-4 pt-2.5 pb-2 rounded-2xl text-sm leading-relaxed
                                {{ $isBuyer
                                    ? 'bg-gold-500 text-white rounded-br-sm'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-bl-sm' }}">
                                <p class="whitespace-pre-wrap break-words">{{ $message->body }}<span class="inline-block w-10 h-3 align-bottom shrink-0"></span></p>
                                <span class="absolute bottom-1.5 right-3 text-xs {{ $isBuyer ? 'text-yellow-100' : 'text-gray-400 dark:text-gray-500' }}">
                                    {{ $message->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No hay mensajes en esta conversación.</p>
                @endforelse

                </div>{{-- fin área scrollable --}}

                {{-- Botón ir al final --}}
                <button x-show="!atBottom"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-90"
                        @click="scrollDown()"
                        class="absolute bottom-4 right-4 z-10 w-9 h-9 rounded-full bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 shadow-xl ring-1 ring-gray-200 dark:ring-gray-600 flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

            </div>

        </div>
    </div>
</x-app-layout>
