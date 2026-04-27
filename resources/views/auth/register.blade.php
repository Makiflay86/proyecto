<x-guest-layout>

    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-1">Crea tu cuenta</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Únete a Venalia y empieza a comprar y vender</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
        @csrf

        {{-- Nombre --}}
        <div class="field-group">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Nombre <span class="asterisk {{ $errors->has('name') ? 'text-red-500' : 'text-gray-400' }}">*</span>
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                      required autocomplete="name"
                   class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent transition text-sm">
            @if($errors->has('name'))
                <p class="field-error mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $errors->first('name') }}</p>
            @endif
        </div>

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
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Contraseña <span class="asterisk {{ $errors->has('password') ? 'text-red-500' : 'text-gray-400' }}">*</span>
            </label>
            <div class="relative">
                <input id="password" type="password" name="password"
                       required autocomplete="new-password"
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
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Entre 8 y 16 caracteres, incluyendo al menos un símbolo (ej: !, @, #, $).</p>
            @if($errors->has('password'))
                <p class="field-error mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $errors->first('password') }}</p>
            @endif
        </div>

        {{-- Confirmar contraseña --}}
        <div class="field-group">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Confirmar contraseña <span class="asterisk {{ $errors->has('password_confirmation') || $errors->has('password') ? 'text-red-500' : 'text-gray-400' }}">*</span>
            </label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       class="w-full px-4 py-2.5 pr-11 rounded-xl border {{ $errors->has('password_confirmation') || $errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:border-transparent transition text-sm">
                <button type="button" onclick="togglePassword('password_confirmation')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <svg id="password_confirmation-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="password_confirmation-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @if($errors->has('password_confirmation'))
                <p class="field-error mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>

        {{-- Botón --}}
        <button type="submit"
                class="w-full bg-gold-600 hover:bg-gold-700 text-white font-bold py-3 rounded-xl transition shadow-md hover:shadow-lg">
            Crear cuenta
        </button>

        {{-- Login --}}
        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-gold-600 dark:text-gold-400 font-semibold hover:underline">
                Inicia sesión
            </a>
        </p>
    </form>

    @vite('resources/js/auth.js')
    <script>
        document.getElementById('name').addEventListener('change', function () { limpiarError('name'); });
        document.getElementById('email').addEventListener('change', function () { limpiarError('email'); });
        document.getElementById('password').addEventListener('change', function () { limpiarError('password'); });
        document.getElementById('password_confirmation').addEventListener('change', function () { limpiarError('password_confirmation'); });
    </script>

</x-guest-layout>
