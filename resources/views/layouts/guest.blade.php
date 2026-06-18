<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .page-transition {
                animation: fadeIn 0.5s ease-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-white min-h-screen">
        <div class="min-h-screen flex flex-col lg:flex-row">
            <!-- Left Side: Branding & Logo -->
            <div class="hidden lg:flex lg:w-1/2 bg-cyan-600 relative overflow-hidden items-center justify-center p-12">
                <!-- Decorative Elements -->
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute -top-[10%] -left-[10%] w-[60%] h-[60%] rounded-full bg-cyan-500/30 blur-[120px]"></div>
                    <div class="absolute bottom-[10%] right-[5%] w-[40%] h-[40%] rounded-full bg-blue-700/20 blur-[100px]"></div>
                </div>

                <div class="relative z-10 text-center">
                    <div class="inline-flex p-6 bg-white rounded-[3rem] mb-10 shadow-2xl">
                        <x-application-logo class="w-20 h-20 object-contain" />
                    </div>
                    <h1 class="text-6xl font-black text-white tracking-tighter mb-6 text-center">SIABAN</h1>
                    <p class="text-cyan-100 text-xl font-bold max-w-md mx-auto leading-relaxed text-center opacity-90 uppercase tracking-widest">
                        Sistem Informasi Deteksi Banjir Cerdas
                    </p>
                </div>

                <!-- Footer Text -->
                <div class="absolute bottom-8 left-12 right-12 flex justify-between text-cyan-200/50 text-[10px] font-black uppercase tracking-[0.3em]">
                    <span>© 2026 SIABAN</span>
                    <span>AI-POWERED MONITORING</span>
                </div>
            </div>

            <!-- Right Side: Auth Forms -->
            <div class="w-full lg:w-1/2 flex flex-col bg-white min-h-screen">
                <div class="w-full max-w-md mx-auto px-10 flex flex-col justify-center min-h-screen py-12 page-transition">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden flex justify-center mb-10">
                        <a href="/" class="flex items-center gap-3">
                            <x-application-logo class="w-12 h-12 object-contain" />
                            <span class="text-3xl font-black tracking-tight text-slate-900 uppercase">SIABAN</span>
                        </a>
                    </div>

                    <div class="bg-white">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
