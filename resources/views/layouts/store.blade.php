<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'Venalia') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/sass/app.scss'])

        @livewireStyles

        {{-- Anti-flash dark mode --}}
        @include('partials.dark-mode-init')
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

        <div x-data="{ searchOpen: false }">

            {{-- Navbar --}}
            <header class="sticky top-0 z-50 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center h-16">

                        {{-- Logo --}}
                        <a href="{{ route('store.index') }}" class="shrink-0 mr-4">
                            <img src="{{ asset('images/logo.svg') }}" alt="Venalia" class="h-10 w-auto">
                        </a>

                        {{-- Buscador (desktop) — centrado --}}
                        <div class="hidden sm:flex flex-1 justify-center px-4">
                            <form action="{{ route('store.index') }}" method="GET" class="w-full max-w-lg">
                                <div class="relative">
                                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                                           placeholder="Buscar productos..."
                                           x-on:input.debounce.300ms="$dispatch('set-buscar', { value: $el.value })"
                                           class="w-full pl-4 pr-10 py-2 rounded-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gold-400 text-sm transition">
                                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gold-500 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="flex items-center gap-2 ml-auto sm:ml-0 shrink-0">

                            {{-- Icono búsqueda (móvil) --}}
                            <button @click="searchOpen = !searchOpen" class="sm:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                                </svg>
                            </button>

                            {{-- Toggle dark mode --}}
                            <button onclick="toggleTheme()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <svg class="w-5 h-5 text-yellow-500 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                                </svg>
                                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                                </svg>
                            </button>

                            @auth
                                @php $unreadNav = Auth::user()->unreadThreadsCount() @endphp
                                {{-- Menú de usuario autenticado --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                            class="relative flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm font-medium text-gray-800 dark:text-white">
                                        <div class="relative w-6 h-6">
                                            @if(Auth::user()->avatar)
                                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt=""
                                                     class="w-6 h-6 rounded-full object-cover">
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-gold-500 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span id="unread-dot" class="hidden absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-gray-800"></span>
                                        </div>
                                        <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                                        @if(Auth::user()->is_admin)
                                        <a href="{{ route('dashboard') }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Panel de gestión
                                        </a>
                                        @endif
                                        <a href="{{ route('publish.create') }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Publicar producto
                                        </a>
                                        <a href="{{ route('store.favorites') }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 text-red-400" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            Mis favoritos
                                        </a>
                                        <a href="{{ route('chat.index') }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                            </svg>
                                            Mis mensajes
                                            <span id="unread-badge-count" class="{{ $unreadNav > 0 ? '' : 'hidden' }} ml-auto bg-indigo-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center">{{ $unreadNav > 99 ? '99+' : $unreadNav }}</span>
                                        </a>
                                        <a href="{{ route('profile.store') }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Mi perfil
                                        </a>
                                        <hr class="my-1 border-gray-200 dark:border-gray-700">
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 transition">
                                                Cerrar sesión
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}"
                                   class="text-sm font-medium text-gray-700 dark:text-gray-100 hover:text-gold-600 dark:hover:text-gold-400 transition px-3 py-1.5">
                                    Entrar
                                </a>
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center text-sm font-semibold bg-gold-500 hover:bg-gold-600 dark:bg-gold-600 dark:hover:bg-gold-500 text-gray-900 dark:text-white px-4 py-1.5 rounded-full transition shadow-sm">
                                    Registrarse
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- Buscador (móvil, desplegable) --}}
                    <div x-show="searchOpen" x-transition class="sm:hidden pb-3">
                        <form action="{{ route('store.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="buscar" value="{{ request('buscar') }}"
                                       placeholder="Buscar productos..."
                                       x-on:input.debounce.300ms="$dispatch('set-buscar', { value: $el.value })"
                                       class="w-full pl-4 pr-10 py-2 rounded-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Contenido --}}
            <main>
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <div
                x-data="{
                    activeModal: null,
                    _wasLocked: false,
                    init() {
                        this.$watch('activeModal', val => {
                            if (val) {
                                this._wasLocked = document.body.classList.contains('overflow-hidden');
                                document.body.classList.add('overflow-hidden');
                            } else {
                                if (!this._wasLocked) document.body.classList.remove('overflow-hidden');
                            }
                        });
                    }
                }"
                x-on:open-legal.window="activeModal = $event.detail"
            >

                <footer class="mt-16 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <img src="{{ asset('images/logo.svg') }}" alt="Venalia" class="h-8 w-auto mx-auto mb-3 opacity-60">
                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-3">© {{ date('Y') }} Venalia — Compra y vende con confianza.</p>
                        <nav class="flex flex-wrap justify-center gap-x-4 gap-y-1">
                            <button @click="activeModal = 'aviso'"     class="text-xs text-gray-400 dark:text-gray-500 hover:text-indigo-500 dark:hover:text-indigo-400 transition">Aviso Legal</button>
                            <button @click="activeModal = 'privacidad'" class="text-xs text-gray-400 dark:text-gray-500 hover:text-indigo-500 dark:hover:text-indigo-400 transition">Política de Privacidad</button>
                            <button @click="activeModal = 'cookies'"   class="text-xs text-gray-400 dark:text-gray-500 hover:text-indigo-500 dark:hover:text-indigo-400 transition">Política de Cookies</button>
                            <button @click="activeModal = 'terminos'"  class="text-xs text-gray-400 dark:text-gray-500 hover:text-indigo-500 dark:hover:text-indigo-400 transition">Términos y Condiciones</button>
                        </nav>
                    </div>
                </footer>

                {{-- Modal legal --}}
                <div
                    x-show="activeModal !== null"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[10000] flex items-end sm:items-center justify-center p-4"
                    style="display: none;"
                >
                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="activeModal = null"></div>

                    {{-- Panel --}}
                    <div
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                        class="relative z-10 w-full max-w-2xl max-h-[80vh] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl flex flex-col"
                    >
                        {{-- Cabecera --}}
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 shrink-0">
                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Información legal</span>
                            <button @click="activeModal = null" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Contenido scrollable --}}
                        <div class="overflow-y-auto px-6 py-5 text-sm text-gray-600 dark:text-gray-300 leading-relaxed space-y-2">
                            <template x-if="activeModal === 'aviso'">
                                <div>@include('partials.legal.aviso-legal')</div>
                            </template>
                            <template x-if="activeModal === 'privacidad'">
                                <div>@include('partials.legal.privacidad')</div>
                            </template>
                            <template x-if="activeModal === 'cookies'">
                                <div>@include('partials.legal.cookies')</div>
                            </template>
                            <template x-if="activeModal === 'terminos'">
                                <div>@include('partials.legal.terminos')</div>
                            </template>
                        </div>

                        {{-- Pie del modal --}}
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 shrink-0 text-right">
                            <button @click="activeModal = null" class="px-4 py-2 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        @livewireScripts

        @stack('scripts')

        <x-cookie-banner />

        @auth
        <script>
            (function () {
                function updateUnread() {
                    fetch('/mensajes/no-leidos', {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(function(r) {
                        if (!r.ok) return;
                        return r.json();
                    })
                    .then(function(data) {
                        if (!data) return;
                        var dot   = document.getElementById('unread-dot');
                        var badge = document.getElementById('unread-badge-count');
                        if (data.count > 0) {
                            if (dot)   dot.classList.remove('hidden');
                            if (badge) { badge.textContent = data.count > 99 ? '99+' : data.count; badge.classList.remove('hidden'); }
                        } else {
                            if (dot)   dot.classList.add('hidden');
                            if (badge) badge.classList.add('hidden');
                        }
                    });
                }

                updateUnread();
                setInterval(updateUnread, 5000);
            })();
        </script>
        @endauth
    </body>
</html>
