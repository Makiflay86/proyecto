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
                <a href="{{ route('products.create') }}"
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
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl border border-gray-100 dark:border-gray-600 overflow-hidden hover:shadow-lg transition-all duration-300 group relative">

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
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ number_format($product->precio, 2, ',', '.') }} €</span>
                                        <a href="{{ route('store.show', $product) }}"
                                           class="text-sm text-gold-600 dark:text-gold-400 hover:text-gold-800 dark:hover:text-gold-300 font-medium transition">
                                            Ver →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

        </div>

    </div>
</x-store-layout>
