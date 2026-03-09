{{--
    Sidebar de navegación.

    Comportamiento responsive:
    - Móvil  (< lg): fixed, oculto por defecto (-translate-x-full).
                     Se abre/cierra mediante sidebarOpen (Alpine, definido en app.blade.php).
                     z-50 para aparecer sobre el overlay y el contenido.
    - Desktop (lg+): sticky top-0, siempre visible, translate-x-0 forzado.
--}}
<nav
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed top-0 left-0 h-screen z-50
           lg:sticky lg:translate-x-0 lg:z-auto
           bg-white dark:bg-gray-900 shadow-xl
           flex flex-col p-4
           overflow-y-auto
           transition-transform duration-300 ease-in-out"
    style="width: 300px;"
>
    {{-- Botón cerrar (solo visible en móvil) --}}
    <button
        @click="sidebarOpen = false"
        class="lg:hidden absolute top-4 right-4 p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
        aria-label="Cerrar menú"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Logo --}}
    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2" wire:navigate.hover>
            <i class="bi bi-car-front-fill text-2xl text-red-600"></i>
            <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tighter">mi-app</span>
        </a>
    </div>

    {{-- Links de navegación --}}
    <ul class="flex flex-col gap-2 mb-auto">
        <li>
            <a href="{{ route('dashboard') }}"
                @click="sidebarOpen = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-red-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                wire:navigate.hover>
                <i class="bi bi-people-fill text-lg"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('products.index') }}"
                @click="sidebarOpen = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('products.index') ? 'bg-red-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                wire:navigate.hover>
                <i class="bi bi-truck text-lg"></i> Productos
            </a>
        </li>
    </ul>

    {{-- Barra de búsqueda --}}
    <div class="my-4">
        <livewire:product-search />
    </div>

    {{-- Toggle dark / light mode --}}
    <div class="px-1 mb-3">
        <button
            onclick="toggleTheme()"
            id="theme-toggle-btn"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 transition-all"
        >
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 text-yellow-500 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
                <svg class="w-4 h-4 text-gray-500 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                    <span class="block dark:hidden">Modo oscuro</span>
                    <span class="hidden dark:block">Modo claro</span>
                </span>
            </div>
            <div class="relative w-10 h-5 rounded-full bg-gray-300 dark:bg-indigo-600 transition-colors duration-300">
                <div class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-300 dark:translate-x-5"></div>
            </div>
        </button>
    </div>

    <hr class="border-gray-200 dark:border-gray-700">

    {{-- Footer de usuario --}}
    <div class="user-footer mt-2">
        @auth
            <a href="{{ route('profile.edit') }}"
               @click="sidebarOpen = false"
               class="block p-4 rounded-2xl mb-4 border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all"
               wire:navigate.hover>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500 tracking-wider">Editar perfil</span>
                        <span class="block text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </a>

            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button id="cerrarsesion" type="submit"
                        class="flex w-full items-center justify-center gap-2 text-red-600 border border-red-200 dark:border-red-900 rounded-full py-2.5 font-bold text-sm hover:bg-red-50 dark:hover:bg-red-950 transition-all">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
            </form>
        @endauth
    </div>
</nav>
