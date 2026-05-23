<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 leading-tight">
            {{ __('Lupa Password?') }}
        </h2>
        <p class="text-slate-500 mt-2 font-medium leading-relaxed">
            {{ __('Jangan khawatir. Masukkan alamat email Anda di bawah ini, dan kami akan mengirimkan permintaan reset password ke Admin sistem.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1" />
            <x-text-input id="email" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-cyan-500/20" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4">
            <x-primary-button class="w-full justify-center py-4 bg-cyan-600 rounded-2xl font-black uppercase tracking-widest text-xs shadow-lg shadow-cyan-200 hover:bg-cyan-700 transition-all border-none">
                {{ __('Kirim Permintaan ke Admin') }}
            </x-primary-button>
            
            <a href="{{ route('login') }}" class="text-center text-xs font-black text-slate-400 uppercase tracking-widest hover:text-cyan-600 transition-colors">
                Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>
