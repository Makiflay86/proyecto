<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Venalia') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/sass/app.scss'])

        {{-- Anti-flash dark mode --}}
        @include('partials.dark-mode-init')
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

        <div class="min-h-screen flex">

            {{-- Panel izquierdo — solo desktop --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gray-900 flex-col items-center justify-center p-12 relative overflow-hidden">

                {{-- Decoración de fondo --}}
                <div class="absolute top-0 left-0 w-96 h-96 bg-gold-500/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-80 h-80 bg-gold-500/10 rounded-full translate-x-1/3 translate-y-1/3"></div>
                <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-gold-500/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>

                <div class="relative z-10 text-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="Venalia" class="h-20 w-auto mx-auto mb-10">
                    <h1 class="text-4xl font-extrabold text-white leading-tight">
                        Compra y vende<br>con confianza
                    </h1>
                    <p class="mt-4 text-gray-400 max-w-xs mx-auto leading-relaxed">
                        La plataforma de segunda mano donde cada objeto encuentra un nuevo hogar.
                    </p>

                    <div class="mt-12 flex flex-col gap-4 text-left max-w-xs mx-auto">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gold-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-300">Miles de productos de segunda mano</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gold-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-300">Publicación gratuita y sencilla</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gold-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-300">Contacto directo entre compradores y vendedores</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel derecho — formulario --}}
            <div class="flex-1 flex flex-col items-center justify-center p-8 bg-white dark:bg-gray-900">

                {{-- Logo (solo móvil) --}}
                <a href="{{ route('store.index') }}" class="lg:hidden mb-8">
                    <img src="{{ asset('images/logo.svg') }}" alt="Venalia" class="h-14 w-auto">
                </a>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

                <a href="{{ route('store.index') }}"
                   class="mt-8 text-sm text-gray-400 dark:text-gray-500 hover:text-gold-500 transition">
                    ← Volver a la tienda
                </a>
            </div>

        </div>

    </body>
</html>
