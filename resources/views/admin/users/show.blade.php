<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.users.index') }}"
               wire:navigate
               class="shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="min-w-0">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight truncate">
                    {{ $user->name }}
                </h2>
                <div class="flex items-center gap-2 flex-wrap mt-0.5">
                    @if ($user->is_admin)
                        <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            <i class="bi bi-shield-fill-check"></i> Admin
                        </span>
                    @endif
                    @if ($user->isOnline())
                        <span class="inline-flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400 font-medium">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span> En línea
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-full alert-fade">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-full alert-fade">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tarjeta principal --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center gap-5">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                             class="w-16 h-16 rounded-full object-cover shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gold-500 flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            Registrado el {{ $user->created_at->format('d/m/Y \a \l\a\s H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->products_count }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Productos publicados</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->liked_products_count }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Favoritos guardados</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 text-center">
                    <p class="text-2xl font-bold {{ $user->isOnline() ? 'text-green-500' : 'text-gray-400' }}">
                        {{ $user->isOnline() ? 'Ahora' : ($user->last_seen_at ? $user->last_seen_at->diffForHumans() : '—') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Último acceso</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 text-center">
                    <p class="text-2xl font-bold {{ $user->email_verified_at ? 'text-green-500' : 'text-amber-500' }}">
                        {{ $user->email_verified_at ? 'Sí' : 'No' }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Email verificado</p>
                </div>
                @php $avg = $user->averageRating(); $total = $user->ratingsCount(); @endphp
                <div class="col-span-2 sm:col-span-1 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-5 h-5 {{ $avg !== null ? 'text-gold-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <p class="text-2xl font-bold {{ $avg !== null ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ $avg !== null ? number_format($avg, 1) : '—' }}
                        </p>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        {{ $total > 0 ? $total . ' ' . ($total === 1 ? 'valoración' : 'valoraciones') : 'Sin valoraciones' }}
                    </p>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row gap-3"
                 x-data="{ openAdmin: false, openDelete: false }">
                    {{-- Botón Editar --}}
                    <a href="{{ route('admin.users.edit', $user) }}"
                       wire:navigate.hover
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold bg-gold-600 text-white hover:bg-gold-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>

                @if ($user->id !== Auth::id())

                    {{-- Botón Hacer/Quitar admin --}}
                    <button @click="openAdmin = true"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold transition-all
                                   {{ $user->is_admin
                                       ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900/50 border border-amber-200 dark:border-amber-800'
                                       : 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400 hover:bg-gold-200 dark:hover:bg-gold-900/50 border border-gold-200 dark:border-gold-700' }}">
                        <i class="bi bi-shield{{ $user->is_admin ? '-x' : '-fill-check' }}"></i>
                        {{ $user->is_admin ? 'Quitar rol admin' : 'Hacer administrador' }}
                    </button>

                    {{-- Botón Eliminar --}}
                    <button @click="openDelete = true"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-all sm:ml-auto">
                        <i class="bi bi-trash"></i> Eliminar usuario
                    </button>

                    {{-- Modal: Hacer/Quitar admin --}}
                    <div x-show="openAdmin"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
                         @keydown.escape.window="openAdmin = false">
                        <div x-show="openAdmin"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.outside="openAdmin = false"
                             class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">
                            <div class="flex justify-center mb-5">
                                <div class="{{ $user->is_admin ? 'bg-amber-100 dark:bg-amber-900/50' : 'bg-gold-100 dark:bg-gold-900/30' }} rounded-full p-4">
                                    <i class="bi bi-shield{{ $user->is_admin ? '-x' : '-fill-check' }} text-2xl {{ $user->is_admin ? 'text-amber-600 dark:text-amber-400' : 'text-gold-600 dark:text-gold-400' }}"></i>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">
                                {{ $user->is_admin ? '¿Quitar rol de administrador?' : '¿Hacer administrador?' }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                {{ $user->is_admin
                                    ? 'Se quitarán los permisos de administrador a'
                                    : 'Se otorgarán permisos de administrador a' }}
                                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->name }}</span>.
                            </p>
                            <div class="mt-6 flex gap-3">
                                <button @click="openAdmin = false"
                                        class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                    Cancelar
                                </button>
                                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="flex-1">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="w-full px-4 py-2.5 rounded-lg font-medium transition shadow-md
                                                   {{ $user->is_admin
                                                       ? 'bg-amber-500 hover:bg-amber-600 text-white'
                                                       : 'bg-gold-600 hover:bg-gold-700 text-white' }}">
                                        {{ $user->is_admin ? 'Sí' : 'Sí' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal: Eliminar usuario --}}
                    <div x-show="openDelete"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
                         @keydown.escape.window="openDelete = false">
                        <div x-show="openDelete"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.outside="openDelete = false"
                             class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">
                            <div class="flex justify-center mb-5">
                                <div class="bg-red-100 dark:bg-red-900/50 rounded-full p-4">
                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">
                                ¿Eliminar usuario?
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                Vas a eliminar la cuenta de
                                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->name }}</span>.
                                Esta acción no se puede deshacer.
                            </p>
                            <div class="mt-6 flex gap-3">
                                <button @click="openDelete = false"
                                        class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                    Cancelar
                                </button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-4 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium transition shadow-md">
                                        Sí
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">No puedes modificar tu propia cuenta desde aquí.</p>
                @endif
            </div>

            {{-- Conversaciones del usuario --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Conversaciones
                    <span class="text-sm font-normal text-gray-400">({{ $threads->count() }})</span>
                </h3>

                @if($threads->isEmpty())
                    <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">Este usuario no tiene conversaciones.</p>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($threads as $thread)
                            @php
                                $isBuyer = $thread->thread_user_id === $user->id;
                                $other   = $isBuyer ? $thread->product?->user : $thread->threadUser;
                                $route   = ($thread->product && $thread->threadUser)
                                    ? route('admin.users.conversation', [$thread->threadUser, $thread->product])
                                    : null;
                            @endphp
                            <a href="{{ $route ?? '#' }}"
                               @if(!$route) onclick="return false" @endif
                               class="flex items-center gap-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl px-2 transition group">
                                {{-- Imagen producto --}}
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shrink-0">
                                    @if($thread->product?->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $thread->product->images->first()->path) }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $thread->product?->nombre ?? 'Producto eliminado' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $isBuyer ? 'Comprador' : 'Vendedor' }} · con {{ $other?->name ?? '—' }}
                                        · <span class="italic">{{ $thread->body }}</span>
                                    </p>
                                </div>

                                {{-- Fecha --}}
                                <span class="text-xs text-gray-400 shrink-0">{{ $thread->created_at->diffForHumans() }}</span>

                                <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
