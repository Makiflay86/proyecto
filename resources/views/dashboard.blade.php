<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- {{ __("You're logged in!") }} --}}
                    <h1>¡Bienvenido <strong>{{ Auth::user()->name }}</strong>!</h1>
                    <p class="text-sm">
                        <?php
                            echo "Hoy es: <strong>" . date("d-m-Y") . "</strong>"
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>