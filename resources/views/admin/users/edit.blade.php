<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Editar usuario
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('admin.users.show', $user) }}"
               class="inline-block text-gold-600 dark:text-gold-400 hover:text-gold-700 dark:hover:text-gold-300 font-medium text-sm mb-6 ps-4"
               wire:navigate.hover>
                ← Volver al detalle
            </a>

            {{-- Avatar --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-4 flex items-center gap-5 transition-colors duration-300">
                <div class="relative group shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                             class="w-16 h-16 rounded-full object-cover">
                        <form action="{{ route('admin.users.avatar.delete', $user) }}" method="POST" class="absolute -top-1 -right-1 z-10">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-5 h-5 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow transition"
                                    title="Eliminar foto">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    @else
                        <div class="w-16 h-16 rounded-full bg-gold-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <label for="avatar-input"
                           class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                           title="Cambiar foto">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                    <form action="{{ route('admin.users.avatar', $user) }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                        @csrf
                        <input id="avatar-input" type="file" name="avatar" accept="image/*" class="hidden"
                               onchange="document.getElementById('avatar-form').submit()">
                    </form>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Haz clic en la foto para cambiarla</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 transition-colors duration-300">

                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="field-group">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nombre <span class="asterisk {{ $errors->has('name') ? 'text-red-500' : 'text-gray-400' }}">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                               required autofocus
                               class="w-full bg-white dark:bg-gray-700 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-gold-500 focus:ring-gold-500 transition">
                        @if($errors->has('name'))
                            <p class="field-error mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    {{-- Email --}}
                    <div class="field-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email <span class="asterisk {{ $errors->has('email') ? 'text-red-500' : 'text-gray-400' }}">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                               required
                               class="w-full bg-white dark:bg-gray-700 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-gold-500 focus:ring-gold-500 transition">
                        @if($errors->has('email'))
                            <p class="field-error mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    {{-- Nueva contraseña --}}
                    <div class="field-group">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nueva contraseña
                            <span class="text-gray-400 font-normal">(dejar en blanco para no cambiar)</span>
                        </label>
                        <div class="relative">
                            <input id="password" type="password" name="password"
                                   class="w-full bg-white dark:bg-gray-700 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-gold-500 focus:ring-gold-500 transition pr-11">
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

                    {{-- Confirmar contraseña --}}
                    <div class="field-group">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Confirmar contraseña
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="w-full bg-white dark:bg-gray-700 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-gold-500 focus:ring-gold-500 transition pr-11">
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
                    </div>

                    {{-- Administrador (no se muestra si es tu propia cuenta) --}}
                    @if ($user->id !== Auth::id())
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_admin" id="is_admin" value="1"
                                   {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-gold-600 focus:ring-gold-500">
                            <label for="is_admin" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Administrador
                            </label>
                        </div>
                    @endif

                    <div class="flex items-center pt-2">
                        <button type="submit"
                                class="bg-gold-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-gold-700 transition shadow-md hover:shadow-xl">
                            Guardar cambios
                        </button>
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="ml-6 text-gray-600 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 font-medium transition"
                           wire:navigate.hover>
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @vite(['resources/js/auth.js', 'resources/js/admin-users.js'])
</x-app-layout>
