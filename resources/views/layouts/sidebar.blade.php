<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900 bg-opacity-50 backdrop-blur-sm transition-opacity lg:hidden" @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-100 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col shadow-xl shadow-slate-200/50">
    
    <!-- Sidebar Header -->
    <div class="flex items-center gap-4 px-8 h-24 border-b border-slate-50">
        <x-application-logo class="w-10 h-10 object-contain" />
        <a href="{{ route('dashboard') }}" class="text-2xl font-black tracking-tighter text-slate-900">
            SIABAN
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-900 font-bold' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            Dashboard
        </a>
        
        @if(in_array(Auth::user()->role, ['Admin']))
        <a href="{{ route('monitoring') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('monitoring') ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-900 font-bold' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Monitoring Sistem
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['Admin', 'Staff']))
        <a href="{{ route('prediksi') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('prediksi') ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-900 font-bold' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Prediksi Banjir
        </a>
        @endif

        <a href="{{ route('grafik') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('grafik') ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-900 font-bold' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
            Grafik Tinggi Air
        </a>

        @if(in_array(Auth::user()->role, ['Admin', 'Staff']))
        <a href="{{ route('suggestions.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('suggestions*') ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-900 font-bold' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            Saran Warga
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['Admin']))
        <a href="{{ route('histori') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('histori') ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-900 font-bold' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Histori Data
        </a>
        
        <a href="{{ route('users') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('users*') ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-200 font-black' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-900 font-bold' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            User Management
        </a>
        @endif
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-6 bg-slate-50">
        <div class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-white border border-slate-100 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-cyan-600 flex items-center justify-center text-white font-black text-xs">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="font-black text-slate-900 text-sm truncate">{{ Auth::user()->name }}</span>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">{{ Auth::user()->role ?? 'User' }}</span>
            </div>
        </div>
    </div>
</aside>
