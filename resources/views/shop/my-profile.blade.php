<x-store-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Cabecera del perfil --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center gap-5 flex-wrap">
            <div class="w-16 h-16 rounded-full bg-gold-500 flex items-center justify-center text-white text-2xl font-bold shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white truncate">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Miembro desde {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="flex items-center gap-4 shrink-0">
                <div class="text-center">
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $products->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Productos</p>
                </div>
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

        {{-- Productos creados --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">

                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-5">
                    Mis productos
                    <span class="ml-2 text-xs font-normal text-gray-400 dark:text-gray-500">({{ $products->count() }})</span>
                </h2>

                @if($products->isEmpty())
                    <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">
                        Todavía no has publicado ningún producto.
                    </p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl border border-gray-100 dark:border-gray-600 overflow-hidden hover:shadow-lg transition-all duration-300 group relative"
                                 x-data="{ openDelete: false }">

                                {{-- Badge estado --}}
                                @if($product->isSold())
                                    <div class="absolute top-3 left-3 z-10 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow">
                                        Vendido
                                    </div>
                                @elseif($product->isReserved())
                                    <div class="absolute top-3 left-3 z-10 bg-amber-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow">
                                        Reservado
                                    </div>
                                @endif

                                <div class="h-48 overflow-hidden bg-gray-100 dark:bg-gray-600 {{ $product->isSold() || $product->isReserved() ? 'opacity-50' : '' }}">
                                    @if($product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                             alt="{{ $product->nombre }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-500">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $product->nombre }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $product->descripcion }}</p>
                                    <div class="mt-3">
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ number_format($product->precio, 2, ',', '.') }} €</span>
                                        <div class="mt-2 flex items-center gap-3">
                                            <a href="{{ route('store.show', $product) }}"
                                               class="text-sm text-gold-600 dark:text-gold-400 hover:text-gold-800 dark:hover:text-gold-300 font-medium transition">
                                                Ver →
                                            </a>
                                            <a href="{{ route('publish.edit', $product) }}"
                                               class="text-sm text-gray-500 dark:text-gray-400 hover:text-gold-600 dark:hover:text-gold-400 transition">
                                                Editar
                                            </a>
                                            <button @click="openDelete = true"
                                                    class="text-sm text-red-400 hover:text-red-600 dark:hover:text-red-400 transition">
                                                Eliminar
                                            </button>

                                            {{-- Modal confirmar eliminación --}}
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
                                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center">¿Eliminar producto?</h3>
                                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                                        Vas a eliminar
                                                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $product->nombre }}</span>.
                                                        Esta acción no se puede deshacer.
                                                    </p>
                                                    <div class="mt-6 flex gap-3">
                                                        <button @click="openDelete = false"
                                                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                                            Cancelar
                                                        </button>
                                                        <form action="{{ route('publish.destroy', $product) }}" method="POST" class="flex-1">
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
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

        </div>

    </div>
</x-store-layout>
