<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SIABAN - Sistem Informasi Banjir</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); }
        </style>
    </head>
    <body class="antialiased bg-white text-slate-900 overflow-x-hidden">
        <div class="relative min-h-screen">
            <!-- Background Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-cyan-100/40 blur-[120px]"></div>
                <div class="absolute top-[20%] -right-[5%] w-[40%] h-[40%] rounded-full bg-indigo-100/40 blur-[100px]"></div>
            </div>

            <!-- Navbar -->
            <nav class="max-w-7xl mx-auto px-8 py-10 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-cyan-600 rounded-2xl shadow-xl shadow-cyan-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tighter">SIABAN</span>
                </div>
                <div class="flex items-center gap-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-8 py-3.5 rounded-2xl bg-slate-900 text-white text-sm font-black uppercase tracking-widest hover:shadow-2xl transition-all">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 hover:text-cyan-600 transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-2xl bg-cyan-600 text-white text-sm font-black uppercase tracking-widest hover:shadow-2xl hover:shadow-cyan-200 transition-all">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>

            <!-- Hero Section -->
            <main class="max-w-7xl mx-auto px-8 pt-20 pb-40">
                <div class="grid lg:grid-cols-2 gap-20 items-center">
                    <div class="space-y-12">
                        <div class="space-y-6">
                            <span class="inline-block px-5 py-2 text-[10px] font-black tracking-[0.3em] text-cyan-700 uppercase bg-cyan-50 rounded-xl border border-cyan-100">AI-Powered Monitoring</span>
                            <h1 class="text-6xl lg:text-8xl font-black leading-[0.9] tracking-tighter">
                                Deteksi Dini <br/>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600">Banjir Cerdas.</span>
                            </h1>
                            <p class="text-lg text-slate-500 max-w-lg font-medium leading-relaxed pt-4">
                                Pantau ketinggian air secara real-time dan dapatkan prediksi banjir akurat menggunakan teknologi LSTM Deep Learning untuk keamanan masyarakat.
                            </p>
                        </div>
                        
                        <div class="flex flex-wrap gap-5">
                            <a href="{{ route('login') }}" class="px-10 py-5 rounded-3xl bg-slate-900 text-white font-black text-sm uppercase tracking-widest hover:shadow-2xl hover:shadow-slate-200 transition-all transform hover:-translate-y-1">Mulai Sekarang</a>
                            <div class="flex items-center gap-4 px-8 py-5 rounded-3xl bg-white border border-slate-100 shadow-sm font-black text-xs uppercase tracking-widest text-slate-600">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </span>
                                Monitoring Aktif
                            </div>
                        </div>

                        <div class="pt-12 flex items-center gap-12">
                            <div>
                                <p class="text-4xl font-black text-slate-900 tracking-tighter">99.2%</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Akurasi Model</p>
                            </div>
                            <div class="w-px h-12 bg-slate-100"></div>
                            <div>
                                <p class="text-4xl font-black text-slate-900 tracking-tighter">24/7</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Real-time Data</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -inset-10 bg-gradient-to-tr from-cyan-500/10 to-indigo-500/10 rounded-[4rem] blur-3xl -z-10 animate-pulse"></div>
                        <div class="bg-white border border-slate-100 rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50">
                            <div class="flex justify-between items-center mb-10">
                                <div class="space-y-1">
                                    <h3 class="font-black text-slate-900 uppercase tracking-widest text-sm">Tren Tinggi Air</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Update: Baru saja</p>
                                </div>
                                <div class="px-5 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-xl uppercase tracking-widest border border-emerald-100">Status: Aman</div>
                            </div>
                            <!-- Simple SVG Placeholder for Chart -->
                            <svg viewBox="0 0 400 200" class="w-full h-auto text-cyan-600">
                                <defs>
                                    <linearGradient id="grad" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" style="stop-color:rgb(8,145,178);stop-opacity:0.2" />
                                        <stop offset="100%" style="stop-color:rgb(8,145,178);stop-opacity:0" />
                                    </linearGradient>
                                </defs>
                                <path d="M0 150 Q 50 140, 100 160 T 200 120 T 300 140 T 400 80" fill="none" stroke="currentColor" stroke-width="6" stroke-linecap="round" />
                                <path d="M0 150 Q 50 140, 100 160 T 200 120 T 300 140 T 400 80 V 200 H 0 Z" fill="url(#grad)" />
                                <circle cx="400" cy="80" r="8" fill="currentColor" />
                            </svg>
                            <div class="mt-10 grid grid-cols-2 gap-6">
                                <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Terakhir</p>
                                    <p class="text-3xl font-black text-slate-900 tracking-tight">1.25 <span class="text-sm">m</span></p>
                                </div>
                                <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Prediksi</p>
                                    <p class="text-3xl font-black text-slate-900 tracking-tight uppercase">Aman</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
