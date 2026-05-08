<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Usuarios
            </h2>
            <a href="{{ route('admin.users.create') }}"
               class="bg-gold-600 text-white px-6 py-2 rounded-full shadow-lg hover:bg-gold-700 transition duration-200"
               wire:navigate.hover>
                + Crear usuario
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-full relative mb-4 alert-fade">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-full relative mb-4 alert-fade">
                    {{ session('error') }}
                </div>
            @endif

            <div x-data="{ tab: 'customers' }">

                {{-- Tabs --}}
                <div class="flex gap-2 mb-6">
                    <button @click="tab = 'customers'"
                            :class="tab === 'customers' ? 'bg-gold-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold transition-all">
                        <i class="bi bi-people"></i>
                        Clientes
                        <span :class="tab === 'customers' ? 'bg-gold-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300'"
                              class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full text-xs font-bold">
                            {{ $customers->total() }}
                        </span>
                    </button>
                    <button @click="tab = 'admins'"
                            :class="tab === 'admins' ? 'bg-gold-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold transition-all">
                        <i class="bi bi-shield-check"></i>
                        Administradores
                        <span :class="tab === 'admins' ? 'bg-gold-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300'"
                              class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full text-xs font-bold">
                            {{ $admins->count() }}
                        </span>
                    </button>
                </div>

                {{-- ========================= TAB CLIENTES ========================= --}}
                <div x-show="tab === 'customers'">
                    @if ($customers->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                            <p class="text-gray-400 dark:text-gray-500 text-sm">No hay clientes registrados aún.</p>
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $customers->total() }}</span>
                                    {{ $customers->total() === 1 ? 'cliente' : 'clientes' }}
                                </p>
                            </div>

                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-700 text-left bg-gray-50 dark:bg-gray-700">
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider hidden sm:table-cell">Email</th>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider hidden lg:table-cell">Registrado</th>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider hidden lg:table-cell">Último acceso</th>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($customers as $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                            x-data="{ openDelete: false }">

                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    @if ($user->isOnline())
                                                        <span class="w-2 h-2 rounded-full bg-green-500 shrink-0" title="En línea"></span>
                                                    @else
                                                        <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 shrink-0"></span>
                                                    @endif
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ $user->email }}</td>

                                            <td class="px-6 py-4 text-gray-400 dark:text-gray-500 hidden lg:table-cell">
                                                {{ $user->created_at->format('d/m/Y') }}
                                            </td>

                                            <td class="px-6 py-4 text-gray-400 dark:text-gray-500 hidden lg:table-cell">
                                                {{ $user->last_seen_at ? $user->last_seen_at->diffForHumans() : '—' }}
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-1.5">

                                                    <div class="relative group/tip">
                                                        <a href="{{ route('admin.users.show', $user) }}"
                                                           wire:navigate.hover
                                                           class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gold-100 dark:hover:bg-gold-900/40 hover:text-gold-600 dark:hover:text-gold-400 transition-colors">
                                                            <i class="bi bi-eye text-sm"></i>
                                                        </a>
                                                        <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium bg-gray-900 dark:bg-gray-950 text-white rounded-lg whitespace-nowrap opacity-0 group-hover/tip:opacity-100 transition-opacity z-20">
                                                            Ver
                                                        </span>
                                                    </div>

                                                    <div class="relative group/tip">
                                                        <button @click="openDelete = true"
                                                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-900/50 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                            <i class="bi bi-trash text-sm"></i>
                                                        </button>
                                                        <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium bg-gray-900 dark:bg-gray-950 text-white rounded-lg whitespace-nowrap opacity-0 group-hover/tip:opacity-100 transition-opacity z-20">
                                                            Eliminar
                                                        </span>
                                                    </div>

                                                    <div x-show="openDelete"
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0"
                                                         x-transition:enter-end="opacity-100"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100"
                                                         x-transition:leave-end="opacity-0"
                                                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
                                                         @keydown.escape.window="openDelete = false">
                                                        <div x-show="openDelete"
                                                             x-transition:enter="transition ease-out duration-200"
                                                             x-transition:enter-start="opacity-0 scale-95"
                                                             x-transition:enter-end="opacity-100 scale-100"
                                                             x-transition:leave="transition ease-in duration-150"
                                                             x-transition:leave-start="opacity-100 scale-100"
                                                             x-transition:leave-end="opacity-0 scale-95"
                                                             @click.outside="openDelete = false"
                                                             class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">
                                                            <div class="flex justify-center mb-5">
                                                                <div class="bg-red-100 dark:bg-red-900/50 rounded-full p-4">
                                                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">¿Eliminar usuario?</h3>
                                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                                                Vas a eliminar la cuenta de
                                                                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->name }}</span>.
                                                                Esta acción no se puede deshacer.
                                                            </p>
                                                            <div class="mt-6 flex gap-3">
                                                                <button @click="openDelete = false"
                                                                        class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                                                    Cancelar
                                                                </button>
                                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit"
                                                                            class="w-full px-4 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium transition shadow-md">
                                                                        Sí
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($customers->hasPages())
                            <div class="mt-6">
                                {{ $customers->links() }}
                            </div>
                        @endif
                    @endif
                </div>

                {{-- ========================= TAB ADMINS ========================= --}}
                <div x-show="tab === 'admins'">
                    @if ($admins->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                            <p class="text-gray-400 dark:text-gray-500 text-sm">No hay administradores registrados.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($admins as $user)
                                <div x-data="{ openAdmin: false, openDelete: false }"
                                     class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-gold-900/30 transition-all duration-300">
                                    <div class="p-6 flex flex-col gap-4">

                                        <div class="flex items-center gap-3">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                                     class="w-12 h-12 rounded-full object-cover shrink-0">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-gold-500 flex items-center justify-center text-white font-bold text-lg shrink-0">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="overflow-hidden flex-1 min-w-0">
                                                <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</h3>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $user->email }}</p>
                                            </div>
                                            @if ($user->isOnline())
                                                <span class="w-2.5 h-2.5 rounded-full bg-green-500 shrink-0" title="En línea"></span>
                                            @endif
                                        </div>

                                        <span class="inline-flex items-center gap-1.5 self-start bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <i class="bi bi-shield-fill-check"></i> Administrador
                                        </span>

                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            Desde {{ $user->created_at->format('d/m/Y') }}
                                        </p>

                                        <div class="flex items-center gap-1.5 pt-1 border-t border-gray-100 dark:border-gray-700">

                                            <div class="relative group/tip">
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                   wire:navigate.hover
                                                   class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gold-100 dark:hover:bg-gold-900/40 hover:text-gold-600 dark:hover:text-gold-400 transition-colors">
                                                    <i class="bi bi-eye text-sm"></i>
                                                </a>
                                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium bg-gray-900 dark:bg-gray-950 text-white rounded-lg whitespace-nowrap opacity-0 group-hover/tip:opacity-100 transition-opacity z-20">
                                                    Ver
                                                </span>
                                            </div>

                                            @if ($user->id !== Auth::id())
                                                <div class="relative group/tip ml-auto">
                                                    <button @click="openAdmin = true"
                                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-amber-100 dark:hover:bg-amber-900/50 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                                        <i class="bi bi-shield-minus text-sm"></i>
                                                    </button>
                                                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium bg-gray-900 dark:bg-gray-950 text-white rounded-lg whitespace-nowrap opacity-0 group-hover/tip:opacity-100 transition-opacity z-20">
                                                        Quitar admin
                                                    </span>
                                                </div>

                                                <div class="relative group/tip">
                                                    <button @click="openDelete = true"
                                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-900/50 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                        <i class="bi bi-trash text-sm"></i>
                                                    </button>
                                                    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium bg-gray-900 dark:bg-gray-950 text-white rounded-lg whitespace-nowrap opacity-0 group-hover/tip:opacity-100 transition-opacity z-20">
                                                        Eliminar
                                                    </span>
                                                </div>

                                                <div x-show="openAdmin"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0"
                                                     x-transition:enter-end="opacity-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100"
                                                     x-transition:leave-end="opacity-0"
                                                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
                                                     @keydown.escape.window="openAdmin = false">
                                                    <div x-show="openAdmin"
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 scale-100"
                                                         x-transition:leave-end="opacity-0 scale-95"
                                                         @click.outside="openAdmin = false"
                                                         class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">
                                                        <div class="flex justify-center mb-5">
                                                            <div class="bg-amber-100 dark:bg-amber-900/50 rounded-full p-4">
                                                                <i class="bi bi-shield-x text-2xl text-amber-600 dark:text-amber-400"></i>
                                                            </div>
                                                        </div>
                                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">¿Quitar rol de administrador?</h3>
                                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                                            Se quitarán los permisos de administrador a
                                                            <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->name }}</span>.
                                                        </p>
                                                        <div class="mt-6 flex gap-3">
                                                            <button @click="openAdmin = false"
                                                                    class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                                                Cancelar
                                                            </button>
                                                            <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="flex-1">
                                                                @csrf @method('PATCH')
                                                                <button type="submit"
                                                                        class="w-full px-4 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-medium transition shadow-md">
                                                                    Sí
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div x-show="openDelete"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0"
                                                     x-transition:enter-end="opacity-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100"
                                                     x-transition:leave-end="opacity-0"
                                                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
                                                     @keydown.escape.window="openDelete = false">
                                                    <div x-show="openDelete"
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 scale-100"
                                                         x-transition:leave-end="opacity-0 scale-95"
                                                         @click.outside="openDelete = false"
                                                         class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 max-w-sm w-full">
                                                        <div class="flex justify-center mb-5">
                                                            <div class="bg-red-100 dark:bg-red-900/50 rounded-full p-4">
                                                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">¿Eliminar usuario?</h3>
                                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                                            Vas a eliminar la cuenta de
                                                            <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->name }}</span>.
                                                            Esta acción no se puede deshacer.
                                                        </p>
                                                        <div class="mt-6 flex gap-3">
                                                            <button @click="openDelete = false"
                                                                    class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                                                Cancelar
                                                            </button>
                                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                        class="w-full px-4 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium transition shadow-md">
                                                                    Sí
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="ml-auto text-xs text-gray-300 dark:text-gray-600 italic">Eres tú</span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>{{-- end x-data tabs --}}

        </div>
    </div>
</x-app-layout>
