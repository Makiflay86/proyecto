<nav id="sidebar" class="bg-white shadow-xl flex flex-col p-4 sticky top-0 h-screen" style="width: 300px;">
    
    <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-decoration-none" wire:navigate.hover>
            <i class="bi bi-car-front-fill text-2xl text-red-600"></i>
            <span class="text-2xl font-bold text-gray-900 tracking-tighter">mi-app</span>
        </a>
    </div>

    <ul class="flex flex-col gap-2 mb-auto">
        <li>
            <a href="{{ route('dashboard') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-red-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}"
                wire:navigate.hover>
                <i class="bi bi-people-fill text-lg"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('products.index') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('products.index') ? 'bg-red-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}"
                wire:navigate.hover>
                <i class="bi bi-truck text-lg"></i> Productos
            </a>
        </li>
        {{-- <li>
            <a href="{{ route('extras') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('extras') ? 'bg-red-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="bi bi-plus-circle-fill text-lg"></i> Extras
            </a>
        </li> --}}
    </ul>

    <div class="my-4">
        <livewire:product-search />
    </div>

    <hr class="border-gray-200 my-4">

    <div class="user-footer mt-2">
        @auth
            <a href="{{ route('profile.edit') }}" class="block p-4 rounded-2xl mb-4 border border-gray-100 bg-gray-50 hover:bg-gray-100 transition-all" wire:navigate.hover>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider">Editar perfil</span>
                        <span class="block text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </a>
            
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button id="cerrarsesion" type="submit" class="flex w-full items-center justify-center gap-2 text-red-600 border border-red-200 rounded-full py-2.5 font-bold text-sm hover:bg-red-50 transition-all">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
            </form>
        @endauth
    </div>
</nav>