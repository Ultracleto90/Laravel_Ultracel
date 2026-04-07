<nav x-data="{ open: false }" class="bg-[#111827] border-b border-gray-800">
    
    <style>
        /* Enlaces de navegación principales */
        .space-x-8 > a { color: #9CA3AF !important; }
        .space-x-8 > a:hover { color: #F3F4F6 !important; border-color: #3B82F6 !important; }
        .space-x-8 > a.border-indigo-400, 
        .space-x-8 > a.text-gray-900 { color: #FFFFFF !important; border-color: #3B82F6 !important; }

        /* Menú desplegable de usuario (Dropdown) */
        div[x-show="open"] > div.ring-1 { background-color: #1F2937 !important; border: 1px solid #374151 !important; }
        div[x-show="open"] > div.ring-1 a { color: #D1D5DB !important; }
        div[x-show="open"] > div.ring-1 a:hover { background-color: #374151 !important; color: #FFFFFF !important; }
        
        /* Enlaces Móviles */
        .pt-2.pb-3.space-y-1 > a, .mt-3.space-y-1 > a { color: #9CA3AF !important; }
        .pt-2.pb-3.space-y-1 > a:hover, .mt-3.space-y-1 > a:hover { background-color: #1F2937 !important; color: #FFFFFF !important; border-color: #3B82F6 !important; }
        .pt-2.pb-3.space-y-1 > a.border-indigo-400, .pt-2.pb-3.space-y-1 > a.text-indigo-700 { background-color: #1F2937 !important; color: #FFFFFF !important; border-color: #3B82F6 !important; }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="font-extrabold text-xl tracking-wider text-white">ULTRA<span class="text-blue-500">CEL</span></span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link href="/documentacion" :active="request()->is('documentacion*')">
                        {{ __('Documentación') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-gray-300 bg-gray-800 hover:text-white hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150 shadow-sm border-gray-700">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#111827] border-b border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link href="/documentacion" :active="request()->is('documentacion*')">
                {{ __('Documentación') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-800">
            <div class="px-4">
                <div class="font-medium text-base text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>