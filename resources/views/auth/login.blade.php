<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 mb-2">Selamat Datang</h2>
        <p class="text-slate-500 font-medium text-sm">Masuk untuk memantau sistem secara real-time.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="font-black text-[10px] text-slate-400 uppercase tracking-widest ml-1" />
            <x-text-input id="email" class="block w-full rounded-xl border-none bg-slate-50 focus:ring-2 focus:ring-cyan-500/20 py-3.5 px-5 font-bold text-slate-900 text-sm transition-all placeholder:text-slate-300" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-2" x-data="{ show: false }">
            <div class="flex justify-between items-center ml-1">
                <x-input-label for="password" :value="__('Password')" class="font-black text-[10px] text-slate-400 uppercase tracking-widest" />
                @if (Route::has('password.request'))
                    <a class="text-[9px] font-black text-cyan-600 hover:text-cyan-700 uppercase tracking-widest" href="{{ route('password.request') }}">
                        {{ __('Lupa?') }}
                    </a>
                @endif
            </div>

            <div class="relative">
                <x-text-input id="password" class="block w-full rounded-xl border-none bg-slate-50 focus:ring-2 focus:ring-cyan-500/20 py-3.5 px-5 font-bold text-slate-900 text-sm transition-all placeholder:text-slate-300 pr-10"
                                ::type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••" />
                
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-cyan-600 transition-colors">
                    <template x-if="!show">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </template>
                    <template x-if="show">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </template>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-2 px-1">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-none bg-slate-100 text-cyan-600 shadow-sm focus:ring-2 focus:ring-cyan-500/20 w-4 h-4 transition-all" name="remember">
                <span class="ms-2 text-xs font-bold text-slate-500 group-hover:text-slate-900 transition-colors">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 pt-2">
            <x-primary-button class="w-full justify-center py-3.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 shadow-lg shadow-cyan-200 text-xs font-black uppercase tracking-[0.2em] transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                {{ __('Masuk Sekarang') }}
            </x-primary-button>
            
            @if (Route::has('register'))
                <p class="text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-cyan-600 hover:text-cyan-700 ml-1">Daftar</a>
                </p>
            @endif
        </div>
    </form>
</x-guest-layout>
