<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Usuarios
            </h2>
            <div class="h-10"></div>
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

            {{-- Tabs --}}
            <div class="flex gap-2 mb-6">
                <a href="{{ route('admin.users.index', ['tab' => 'customers']) }}"
                   wire:navigate.hover
                   class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold transition-all
                          {{ $tab === 'customers'
                              ? 'bg-gold-600 text-white shadow-md'
                              : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="bi bi-people"></i>
                    Clientes
                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full text-xs font-bold
                                 {{ $tab === 'customers' ? 'bg-gold-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                        {{ $customers->total() }}
                    </span>
                </a>
                <a href="{{ route('admin.users.index', ['tab' => 'admins']) }}"
                   wire:navigate.hover
                   class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold transition-all
                          {{ $tab === 'admins'
                              ? 'bg-gold-600 text-white shadow-md'
                              : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="bi bi-shield-check"></i>
                    Administradores
                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full text-xs font-bold
                                 {{ $tab === 'admins' ? 'bg-gold-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                        {{ $admins->count() }}
                    </span>
                </a>
            </div>

            {{-- ========================= TAB CLIENTES ========================= --}}
            @if ($tab === 'customers')
                @if ($customers->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                        <p class="text-gray-400 dark:text-gray-500 text-sm">No hay clientes registrados aún.</p>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

                        {{-- Cabecera con contador --}}
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
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">

                                        {{-- Nombre + indicador online --}}
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

                                        {{-- Botones de acción --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-1.5">

                                                {{-- Ver --}}
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                   wire:navigate.hover
                                                   class="group/btn inline-flex items-center gap-1.5 h-8 px-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gold-100 dark:hover:bg-gold-900/40 hover:text-gold-600 dark:hover:text-gold-400 transition-all duration-200">
                                                    <i class="bi bi-eye text-sm shrink-0"></i>
                                                    <span class="text-xs font-medium overflow-hidden max-w-0 group-hover/btn:max-w-[3rem] transition-all duration-200 whitespace-nowrap">Ver</span>
                                                </a>

                                                {{-- Hacer admin --}}
                                                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            onclick="return confirm('¿Convertir a {{ addslashes($user->name) }} en administrador?')"
                                                            class="group/btn inline-flex items-center gap-1.5 h-8 px-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-200">
                                                        <i class="bi bi-shield-plus text-sm shrink-0"></i>
                                                        <span class="text-xs font-medium overflow-hidden max-w-0 group-hover/btn:max-w-[6rem] transition-all duration-200 whitespace-nowrap">Hacer admin</span>
                                                    </button>
                                                </form>

                                                {{-- Eliminar --}}
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('¿Eliminar al usuario {{ addslashes($user->name) }}? Esta acción no se puede deshacer.')"
                                                            class="group/btn inline-flex items-center gap-1.5 h-8 px-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-red-100 dark:hover:bg-red-900/50 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200">
                                                        <i class="bi bi-trash text-sm shrink-0"></i>
                                                        <span class="text-xs font-medium overflow-hidden max-w-0 group-hover/btn:max-w-[4rem] transition-all duration-200 whitespace-nowrap">Eliminar</span>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($customers->hasPages())
                        <div class="mt-6">
                            {{ $customers->appends(['tab' => 'customers'])->links() }}
                        </div>
                    @endif
                @endif
            @endif

            {{-- ========================= TAB ADMINS ========================= --}}
            @if ($tab === 'admins')
                @if ($admins->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                        <p class="text-gray-400 dark:text-gray-500 text-sm">No hay administradores registrados.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($admins as $user)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl dark:hover:shadow-gold-900/30 transition-all duration-300">
                                <div class="p-6 flex flex-col gap-4">

                                    {{-- Avatar + nombre --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gold-500 flex items-center justify-center text-white font-bold text-lg shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="overflow-hidden flex-1 min-w-0">
                                            <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</h3>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $user->email }}</p>
                                        </div>
                                        @if ($user->isOnline())
                                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 shrink-0" title="En línea"></span>
                                        @endif
                                    </div>

                                    {{-- Badge --}}
                                    <span class="inline-flex items-center gap-1.5 self-start bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="bi bi-shield-fill-check"></i> Administrador
                                    </span>

                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        Desde {{ $user->created_at->format('d/m/Y') }}
                                    </p>

                                    {{-- Acciones --}}
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
                                                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            onclick="return confirm('¿Quitar los permisos de administrador a {{ addslashes($user->name) }}?')"
                                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-amber-100 dark:hover:bg-amber-900/50 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                                        <i class="bi bi-shield-minus text-sm"></i>
                                                    </button>
                                                </form>
                                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium bg-gray-900 dark:bg-gray-950 text-white rounded-lg whitespace-nowrap opacity-0 group-hover/tip:opacity-100 transition-opacity z-20">
                                                    Quitar admin
                                                </span>
                                            </div>

                                            <div class="relative group/tip">
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('¿Eliminar al usuario {{ addslashes($user->name) }}? Esta acción no se puede deshacer.')"
                                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-900/50 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                        <i class="bi bi-trash text-sm"></i>
                                                    </button>
                                                </form>
                                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium bg-gray-900 dark:bg-gray-950 text-white rounded-lg whitespace-nowrap opacity-0 group-hover/tip:opacity-100 transition-opacity z-20">
                                                    Eliminar
                                                </span>
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
            @endif

        </div>
    </div>
</x-app-layout>
