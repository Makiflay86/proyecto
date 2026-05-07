<div
    x-data="{
        show: false,
        init() {
            this.show = !localStorage.getItem('cookies_accepted');
            if (this.show) document.body.classList.add('overflow-hidden');
            if (localStorage.getItem('cookies_accepted') === '1') this.loadGA();
        },
        accept() {
            localStorage.setItem('cookies_accepted', '1');
            this.show = false;
            document.body.classList.remove('overflow-hidden');
            this.loadGA();
        },
        decline() {
            localStorage.setItem('cookies_accepted', '0');
            this.show = false;
            document.body.classList.remove('overflow-hidden');
        },
        loadGA() {
            if (document.getElementById('ga-script')) return;
            var s = document.createElement('script');
            s.id  = 'ga-script';
            s.src = 'https://www.googletagmanager.com/gtag/js?id=G-YDFK7HTG9W';
            s.async = true;
            document.head.appendChild(s);
            window.dataLayer = window.dataLayer || [];
            function gtag(){ window.dataLayer.push(arguments); }
            window.gtag = gtag;
            gtag('js', new Date());
            gtag('config', 'G-YDFK7HTG9W', {
                @auth
                user_id: '{{ Auth::id() }}',
                user_properties: {
                    logged_in: 'true',
                    user_name: '{{ Auth::user()->name }}'
                }
                @else
                user_properties: {
                    logged_in: 'false'
                }
                @endauth
            });
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
    style="display: none;"
>
    {{-- Overlay bloqueante — no tiene @click para que no se pueda cerrar --}}
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

    {{-- Modal centrado --}}
    <div
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl p-6"
    >
        {{-- Icono --}}
        <div class="flex justify-center mb-4">
            <span class="text-4xl select-none" aria-hidden="true">&#127850;</span>
        </div>

        {{-- Título --}}
        <h2 class="text-lg font-semibold text-center text-gray-900 dark:text-white mb-2">
            Usamos cookies
        </h2>

        {{-- Texto --}}
        <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-6">
            Utilizamos cookies propias y de terceros para mejorar tu experiencia de navegación.
            Puedes aceptarlas o rechazarlas. Para más información consulta nuestra
            <a href="#" @click.prevent="$dispatch('open-legal', 'cookies')" class="underline text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                política de cookies
            </a>.
        </p>

        {{-- Botones --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <button
                @click="decline()"
                class="flex-1 px-4 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium"
            >
                Rechazar
            </button>
            <button
                @click="accept()"
                class="flex-1 px-4 py-2.5 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition-colors"
            >
                Aceptar todas
            </button>
        </div>
    </div>
</div>
