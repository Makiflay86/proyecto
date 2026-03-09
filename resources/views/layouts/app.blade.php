<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/sass/app.scss'])

        @livewireStyles

        {{-- Anti-flash: must be inline in <head> because app.js is deferred by Vite --}}
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
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex transition-colors duration-300">

            @include('layouts.navigation')

            <div class="flex-1 flex flex-col">
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-900 transition-colors duration-300">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
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
