<x-store-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Cabecera del perfil --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center gap-5 flex-wrap">
            <div class="relative shrink-0 group">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                         class="w-16 h-16 rounded-full object-cover">
                    <form action="{{ route('profile.avatar.delete') }}" method="POST" class="absolute -top-1 -right-1 z-10">
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
                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                    @csrf
                    <input id="avatar-input" type="file" name="avatar" accept="image/*" class="hidden"
                           onchange="document.getElementById('avatar-form').submit()">
                </form>
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white truncate">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Miembro desde {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
            @php
                $avg   = $user->averageRating();
                $total = $user->ratingsCount();
            @endphp
            <div class="text-center shrink-0 border-l border-gray-100 dark:border-gray-700 pl-5">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-5 h-5 {{ $avg !== null ? 'text-gold-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span class="text-2xl font-bold {{ $avg !== null ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                        {{ $avg !== null ? number_format($avg, 1) : '—' }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $total > 0 ? $total . ' ' . ($total === 1 ? 'valoración' : 'valoraciones') : 'Sin valoraciones' }}
                </p>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                <a href="{{ route('publish.create') }}"
                   class="inline-flex items-center gap-1.5 bg-gold-500 hover:bg-gold-600 text-white font-semibold px-4 py-2.5 rounded-full text-sm transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Añadir producto
                </a>
            </div>
        </div>

        {{-- Editar nombre y email --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Información personal</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Actualiza tu nombre y dirección de correo.</p>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                    <input id="name" name="name" type="text"
                           value="{{ old('name', $user->name) }}"
                           required autocomplete="name"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email', $user->email) }}"
                           required autocomplete="username"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                            Email no verificado.
                            <button form="send-verification" class="underline hover:text-yellow-700 dark:hover:text-yellow-300 transition">
                                Reenviar verificación
                            </button>
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-1">
                    <button type="submit"
                            class="bg-gold-500 hover:bg-gold-600 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition shadow-sm">
                        Guardar cambios
                    </button>
                    @if(session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                           class="text-sm text-green-600 dark:text-green-400">Guardado.</p>
                    @endif
                </div>
            </form>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Contraseña</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Usa una contraseña segura para proteger tu cuenta.</p>

            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña actual</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                    @error('current_password', 'updatePassword')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nueva contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                    @error('password', 'updatePassword')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-400 transition">
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-1">
                    <button type="submit"
                            class="bg-gold-500 hover:bg-gold-600 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition shadow-sm">
                        Actualizar contraseña
                    </button>
                    @if(session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                           class="text-sm text-green-600 dark:text-green-400">Actualizada.</p>
                    @endif
                </div>
            </form>
        </div>

    </div>
</x-store-layout>
