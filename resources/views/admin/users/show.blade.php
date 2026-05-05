<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index', ['tab' => $user->is_admin ? 'admins' : 'customers']) }}"
                   wire:navigate.hover
                   class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ $user->name }}
                </h2>
                @if ($user->is_admin)
                    <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                        <i class="bi bi-shield-fill-check"></i> Admin
                    </span>
                @endif
                @if ($user->isOnline())
                    <span class="inline-flex items-center gap-1.5 text-xs text-green-600 dark:text-green-400 font-medium">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> En línea
                    </span>
                @endif
            </div>
            <div class="h-10"></div>
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
                    <div class="w-16 h-16 rounded-full {{ $user->is_admin ? 'bg-red-600' : 'bg-indigo-600' }} flex items-center justify-center text-white font-bold text-2xl shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
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
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
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
            </div>

            {{-- Acciones --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row gap-3">
                @if ($user->id !== Auth::id())
                    <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                                onclick="return confirm('{{ $user->is_admin ? '¿Quitar permisos de administrador a ' . addslashes($user->name) . '?' : '¿Convertir a ' . addslashes($user->name) . ' en administrador?' }}')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold transition-all
                                       {{ $user->is_admin
                                           ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900/50 border border-amber-200 dark:border-amber-800'
                                           : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800' }}">
                            <i class="bi bi-shield{{ $user->is_admin ? '-x' : '-fill-check' }}"></i>
                            {{ $user->is_admin ? 'Quitar rol admin' : 'Hacer administrador' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="sm:ml-auto">
                        @csrf @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('¿Eliminar al usuario {{ addslashes($user->name) }}? Esta acción no se puede deshacer.')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-all">
                            <i class="bi bi-trash"></i> Eliminar usuario
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">No puedes modificar tu propia cuenta desde aquí.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
