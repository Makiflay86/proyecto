<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/sass/app.scss'])

        @livewireStyles

        {{-- Anti-flash: debe estar inline en <head> porque app.js carga diferido con Vite --}}
        <script>
            (function () {
                var t = localStorage.getItem('theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        @stack('head-scripts')
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

        {{-- x-data compartido: sidebarOpen controla el menú en móvil --}}
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex transition-colors duration-300"
             x-data="{ sidebarOpen: false }">

            {{-- Overlay oscuro (solo móvil) al abrir el sidebar --}}
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-gray-900/60 z-40 lg:hidden"
            ></div>

            @include('layouts.navigation')

            <div class="flex-1 flex flex-col min-w-0">

                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-900 transition-colors duration-300">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center gap-3">

                            {{-- Botón hamburguesa — solo visible en móvil --}}
                            <button
                                @click="sidebarOpen = true"
                                class="lg:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                aria-label="Abrir menú"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>

                            <div class="flex-1">{{ $header }}</div>

                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>

            </div>
        </div>

        @livewireScripts

        @stack('scripts')
    </body>
</html>
