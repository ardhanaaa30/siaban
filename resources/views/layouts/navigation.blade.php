<nav x-data="{ open: false }" 
     x-effect="document.body.style.overflow = open ? 'hidden' : ''"
     @keydown.escape.window="open = false"
     @resize.window="if (window.innerWidth >= 640) open = false"
     class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <x-application-logo class="w-10 h-10 object-contain" />
                        <span class="text-xl font-extrabold tracking-tight text-slate-900">SIABAN</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(in_array(Auth::user()->role, ['Admin']))
                    <x-nav-link :href="route('monitoring')" :active="request()->routeIs('monitoring')">
                        {{ __('Monitoring') }}
                    </x-nav-link>
                    @endif

                    @if(in_array(Auth::user()->role, ['Admin', 'Staff']))
                    <x-nav-link :href="route('prediksi')" :active="request()->routeIs('prediksi*')">
                        {{ __('Prediksi') }}
                    </x-nav-link>
                    @endif

                    <x-nav-link :href="route('grafik')" :active="request()->routeIs('grafik')">
                        {{ __('Grafik') }}
                    </x-nav-link>

                    @if(in_array(Auth::user()->role, ['Admin', 'Staff']))
                    <x-nav-link :href="route('suggestions.index')" :active="request()->routeIs('suggestions*')">
                        {{ __('Saran') }}
                    </x-nav-link>
                    @endif

                    @if(in_array(Auth::user()->role, ['Admin']))
                    <x-nav-link :href="route('histori')" :active="request()->routeIs('histori')">
                        {{ __('Histori') }}
                    </x-nav-link>
                    <x-nav-link :href="route('users')" :active="request()->routeIs('users*')">
                        {{ __('Users') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-slate-100 text-sm leading-4 font-semibold rounded-2xl text-slate-600 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                <div>{{ Auth::user()->name }}</div>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-2xl text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 focus:outline-none transition duration-150 ease-in-out shadow-sm border border-slate-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Full Screen Overlay) -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full"
         class="fixed inset-0 z-[100] bg-white sm:hidden flex flex-col h-screen w-screen">
        
        <!-- Mobile Menu Header (Fixed at top) -->
        <div class="flex-none flex items-center justify-between h-16 px-4 border-b border-slate-100 bg-white">
            <div class="flex items-center gap-3">
                <x-application-logo class="w-8 h-8 object-contain" />
                <div class="flex flex-col">
                    <span class="text-base font-black tracking-tight text-slate-900 leading-none">SIABAN</span>
                    <span class="text-[9px] font-bold text-cyan-600 uppercase tracking-widest mt-0.5">Menu Navigasi</span>
                </div>
            </div>
            <button @click="open = false" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all active:scale-90">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Mobile Menu Content (Scrollable but compact) -->
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4 overscroll-contain bg-white">
            <!-- Main Links -->
            <div class="space-y-1">
                <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                    Menu Utama
                </p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-cyan-600 text-white shadow-md shadow-cyan-200 font-black' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-bold border border-transparent' }}">
                    <div class="p-1.5 {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'bg-slate-100' }} rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <span class="text-sm">{{ __('Dashboard') }}</span>
                </a>

                @if(in_array(Auth::user()->role, ['Admin']))
                <a href="{{ route('monitoring') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ request()->routeIs('monitoring') ? 'bg-cyan-600 text-white shadow-md shadow-cyan-200 font-black' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-bold border border-transparent' }}">
                    <div class="p-1.5 {{ request()->routeIs('monitoring') ? 'bg-white/20' : 'bg-slate-100' }} rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span class="text-sm">{{ __('Monitoring Sistem') }}</span>
                </a>
                @endif

                @if(in_array(Auth::user()->role, ['Admin', 'Staff']))
                <a href="{{ route('prediksi') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ request()->routeIs('prediksi*') ? 'bg-cyan-600 text-white shadow-md shadow-cyan-200 font-black' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-bold border border-transparent' }}">
                    <div class="p-1.5 {{ request()->routeIs('prediksi*') ? 'bg-white/20' : 'bg-slate-100' }} rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-sm">{{ __('Prediksi Banjir') }}</span>
                </a>
                @endif

                <a href="{{ route('grafik') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ request()->routeIs('grafik') ? 'bg-cyan-600 text-white shadow-md shadow-cyan-200 font-black' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-bold border border-transparent' }}">
                    <div class="p-1.5 {{ request()->routeIs('grafik') ? 'bg-white/20' : 'bg-slate-100' }} rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    </div>
                    <span class="text-sm">{{ __('Grafik Tinggi Air') }}</span>
                </a>

                @if(in_array(Auth::user()->role, ['Admin']))
                <a href="{{ route('histori') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ request()->routeIs('histori') ? 'bg-cyan-600 text-white shadow-md shadow-cyan-200 font-black' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-bold border border-transparent' }}">
                    <div class="p-1.5 {{ request()->routeIs('histori') ? 'bg-white/20' : 'bg-slate-100' }} rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm">{{ __('Histori Data') }}</span>
                </a>
                @endif
            </div>

            <!-- Admin Specific Section -->
            @if(in_array(Auth::user()->role, ['Admin']))
            <div class="space-y-1">
                <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 mt-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                    Manajemen
                </p>

                <a href="{{ route('users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ request()->routeIs('users*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 font-black' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-bold border border-transparent' }}">
                    <div class="p-1.5 {{ request()->routeIs('users*') ? 'bg-white/20' : 'bg-slate-100' }} rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span class="text-sm">{{ __('User Management') }}</span>
                </a>

                <a href="{{ route('users.reset-requests') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ request()->routeIs('users.reset-requests') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 font-black' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-bold border border-transparent' }}">
                    <div class="p-1.5 {{ request()->routeIs('users.reset-requests') ? 'bg-white/20' : 'bg-slate-100' }} rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <span class="text-sm">{{ __('Permintaan Reset') }}</span>
                </a>
            </div>
            @endif

            <!-- Profile & Settings -->
            <div class="pt-4 border-t border-slate-100">
                
                <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-slate-50 border border-slate-100 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-600 to-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-md shadow-cyan-100">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="font-black text-slate-900 text-sm truncate leading-tight">{{ Auth::user()->name }}</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="px-1.5 py-0.5 rounded-md bg-white text-[9px] font-black text-cyan-700 uppercase tracking-tighter border border-slate-200 shadow-sm">
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('profile.edit') }}" class="flex-1 flex justify-center items-center gap-2 px-3 py-2.5 rounded-xl text-slate-600 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 font-bold transition-all border border-slate-100 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ __('Profil') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex justify-center items-center gap-2 px-3 py-2.5 rounded-xl text-rose-600 bg-rose-50 hover:bg-rose-100 font-bold transition-all border border-rose-100 text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            {{ __('Keluar') }}
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</nav>
