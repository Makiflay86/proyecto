<x-store-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center gap-3 mb-8">
            <div class="bg-indigo-100 dark:bg-indigo-900/30 rounded-full p-2.5">
                <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">Mis mensajes</h1>
                @if(Auth::user()->is_admin)
                    <p class="text-sm text-gray-500 dark:text-gray-400">Todas las conversaciones de clientes</p>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tus conversaciones con Venalia</p>
                @endif
            </div>
        </div>

        @if($threads->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-full p-6">
                        <svg class="w-12 h-12 text-indigo-300 dark:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Sin mensajes todavía</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
                    Contacta con el vendedor desde la página de cualquier producto.
                </p>
                <a href="{{ route('store.index') }}"
                   class="inline-flex items-center gap-2 bg-gold-500 hover:bg-gold-600 text-gray-900 dark:text-white font-semibold px-8 py-3 rounded-full shadow-md hover:shadow-lg transition-all">
                    Explorar productos
                </a>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden">
                @foreach($threads as $thread)
                    @php
                        $isAdmin   = Auth::user()->is_admin;
                        $chatUrl   = $isAdmin
                            ? route('chat.thread', [$thread->product, $thread->threadUser])
                            : route('chat.show', $thread->product);
                        $isUnread  = $thread->read_at === null && $thread->sender_id !== Auth::id();
                    @endphp
                    <div onclick="window.location='{{ $chatUrl }}'"
                         class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer group">

                        {{-- Imagen del producto --}}
                        <div style="position:relative;width:3.5rem;height:3.5rem;min-width:3.5rem;min-height:3.5rem;flex-shrink:0;border-radius:0.75rem;overflow:hidden;background:#374151">
                            @if($thread->product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $thread->product->images->first()->path) }}"
                                     alt="{{ $thread->product->nombre }}"
                                     style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;display:block">
                            @else
                                <div style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#6b7280">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Contenido del hilo --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-baseline justify-between gap-2">
                                <p class="font-semibold text-gray-900 dark:text-white text-sm truncate">
                                    {{ $thread->product->nombre }}
                                </p>
                                <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0">
                                    {{ $thread->created_at->diffForHumans() }}
                                </span>
                            </div>

                            @if($isAdmin)
                                <a href="{{ route('users.profile', $thread->threadUser) }}"
                                   onclick="event.stopPropagation()"
                                   class="inline-flex items-center gap-1 text-xs text-indigo-500 dark:text-indigo-400 font-medium mb-0.5 hover:text-indigo-700 dark:hover:text-indigo-300 transition">
                                    {{ $thread->threadUser->name }}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endif

                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate {{ $isUnread ? 'font-semibold text-gray-700 dark:text-gray-200' : '' }}">
                                @if($thread->sender_id === Auth::id())
                                    <span class="text-gray-400 dark:text-gray-500">Tú: </span>
                                @endif
                                {{ $thread->body }}
                            </p>
                        </div>

                        {{-- Indicador de no leído --}}
                        @if($isUnread)
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 shrink-0"></div>
                        @endif

                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-gray-400 transition shrink-0"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-store-layout>
