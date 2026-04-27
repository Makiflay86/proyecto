<x-guest-layout>

    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-1">¿Olvidaste tu contraseña?</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
        Indícanos tu correo y te enviaremos un enlace para que puedas crear una nueva contraseña.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5" novalidate>
        @csrf

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

        <button type="submit"
                class="w-full bg-gold-600 hover:bg-gold-700 text-white font-bold py-3 rounded-xl transition shadow-md hover:shadow-lg">
            Enviar enlace de recuperación
        </button>

        <a href="{{ route('login') }}"
           class="block w-full text-center border border-gray-600 dark:border-gray-600 hover:border-gold-500 text-gray-300 hover:text-gold-500 font-semibold py-3 rounded-xl transition">
            Volver al inicio de sesión
        </a>
    </form>

    <script>
        function limpiarError(id) {
            const input = document.getElementById(id);
            if (!input) return;
            const group = input.closest('.field-group');
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
            const asterisk = group.querySelector('.asterisk');
            if (asterisk) { asterisk.classList.remove('text-red-500'); asterisk.classList.add('text-gray-400'); }
            const error = group.querySelector('.field-error');
            if (error) error.remove();
        }

        document.getElementById('email').addEventListener('change', function () { limpiarError('email'); });
    </script>

</x-guest-layout>
