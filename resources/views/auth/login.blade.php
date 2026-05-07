<x-guest-layout>

    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-1">Bienvenido de nuevo</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Inicia sesión para acceder a tu cuenta</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        {{-- Email --}}
        <div class="field-group">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Correo electrónico <span class="asterisk {{ $errors->has('email') ? 'text-red-500' : 'text-gray-400' }}">*</span>
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                      required autocomplete="username"
                   class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent transition text-sm">
            @if($errors->has('email'))
                <p class="field-error mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $errors->first('email') }}</p>
            @endif
        </div>

        {{-- Contraseña --}}
        <div class="field-group">
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Contraseña <span class="asterisk {{ $errors->has('password') ? 'text-red-500' : 'text-gray-400' }}">*</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-gold-600 dark:text-gold-400 hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input id="password" type="password" name="password"
                       required autocomplete="current-password"
                       class="w-full px-4 py-2.5 pr-11 rounded-xl border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent transition text-sm">
                <button type="button" onclick="togglePassword('password')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <svg id="password-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="password-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @if($errors->has('password'))
                <p class="field-error mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $errors->first('password') }}</p>
            @endif
        </div>

        {{-- Recuérdame --}}
        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-gray-300 text-gold-500 focus:ring-gold-400">
            <label for="remember_me" class="text-sm text-gray-600 dark:text-gray-400">
                Recuérdame
            </label>
        </div>

        {{-- Botón --}}
        <button type="submit"
                class="w-full bg-gold-600 hover:bg-gold-700 text-white font-bold py-3 rounded-xl transition shadow-md hover:shadow-lg">
            Iniciar sesión
        </button>

        {{-- Registro --}}
        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-gold-600 dark:text-gold-400 font-semibold hover:underline">
                Regístrate gratis
            </a>
        </p>
    </form>

    @vite('resources/js/auth.js')

</x-guest-layout>
