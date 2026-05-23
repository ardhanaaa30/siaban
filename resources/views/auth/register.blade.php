<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-black text-slate-900 mb-2">Daftar Akun</h2>
        <p class="text-slate-500 font-medium text-sm">Bergabunglah untuk akses monitoring lengkap.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Name -->
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Nama Lengkap')" class="font-black text-[10px] text-slate-400 uppercase tracking-widest ml-1" />
                <x-text-input id="name" class="block w-full rounded-xl border-none bg-slate-50 focus:ring-2 focus:ring-cyan-500/20 py-3 px-5 font-bold text-slate-900 text-sm transition-all placeholder:text-slate-300" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Lengkap" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" class="font-black text-[10px] text-slate-400 uppercase tracking-widest ml-1" />
                <x-text-input id="email" class="block w-full rounded-xl border-none bg-slate-50 focus:ring-2 focus:ring-cyan-500/20 py-3 px-5 font-bold text-slate-900 text-sm transition-all placeholder:text-slate-300" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div class="space-y-2" x-data="{ show: false }">
                <x-input-label for="password" :value="__('Password')" class="font-black text-[10px] text-slate-400 uppercase tracking-widest ml-1" />
                <div class="relative">
                    <x-text-input id="password" class="block w-full rounded-xl border-none bg-slate-50 focus:ring-2 focus:ring-cyan-500/20 py-3 px-5 font-bold text-slate-900 text-sm transition-all pr-10"
                                    ::type="show ? 'text' : 'password'"
                                    name="password"
                                    required autocomplete="new-password"
                                    placeholder="Min. 8 Karakter" />
                    
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

            <!-- Confirm Password -->
            <div class="space-y-2" x-data="{ show: false }">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi')" class="font-black text-[10px] text-slate-400 uppercase tracking-widest ml-1" />
                <div class="relative">
                    <x-text-input id="password_confirmation" class="block w-full rounded-xl border-none bg-slate-50 focus:ring-2 focus:ring-cyan-500/20 py-3 px-5 font-bold text-slate-900 text-sm transition-all pr-10"
                                    ::type="show ? 'text' : 'password'"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Ulangi" />
                    
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-cyan-600 transition-colors">
                        <template x-if="!show">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </template>
                        <template x-if="show">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </template>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="flex flex-col gap-4 pt-4">
            <x-primary-button class="w-full justify-center py-3.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 shadow-lg shadow-cyan-200 text-xs font-black uppercase tracking-[0.2em] transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
            
            <p class="text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-cyan-600 hover:text-cyan-700 ml-1">Masuk</a>
            </p>
        </div>
    </form>
</x-guest-layout>
