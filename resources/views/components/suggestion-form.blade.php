<div x-data="{ open: false, sent: @json(session('status') === 'saran-terkirim') }" 
     x-init="if(sent) open = true"
     class="fixed bottom-8 right-8 z-[100]">
    
    <!-- Floating Button -->
    <button @click="open = !open" 
            class="w-16 h-16 bg-cyan-600 hover:bg-cyan-700 text-white rounded-full shadow-2xl shadow-cyan-300 flex items-center justify-center transition-all active:scale-95 group">
        <svg x-show="!open" class="w-8 h-8 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
        <svg x-show="open" x-cloak class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Popup Form -->
    <div x-show="open" 
         x-cloak
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="fixed bottom-28 right-8 w-[calc(100vw-4rem)] md:w-[400px] max-h-[calc(100vh-10rem)] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-y-auto overscroll-contain">
        
        <div class="p-8">
            <div class="flex items-center justify-between mb-6 sticky top-0 bg-white pb-2 z-10">
                <div>
                    <h3 class="text-xl font-black text-slate-900">Kirim Saran</h3>
                    <p class="text-slate-500 text-sm font-medium">Bantu kami jadi lebih baik.</p>
                </div>
                <div class="p-3 bg-cyan-50 rounded-2xl">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            
            @if (session('status') === 'saran-terkirim')
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 animate-bounce">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">Terima kasih! Saran Anda terkirim.</span>
                </div>
            @endif

            <form action="{{ route('suggestions.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-input-label for="nama" :value="__('Nama')" class="text-slate-900 font-bold mb-2 ml-1 text-xs uppercase tracking-widest" />
                        <x-text-input id="nama" name="nama" type="text" class="w-full rounded-2xl border-slate-200 text-sm py-3 px-4 focus:ring-cyan-500/20" 
                            :value="old('nama', auth()->user()?->name)" placeholder="Masukkan nama Anda" required />
                        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="alamat" :value="__('Alamat')" class="text-slate-900 font-bold mb-2 ml-1 text-xs uppercase tracking-widest" />
                        <x-text-input id="alamat" name="alamat" type="text" class="w-full rounded-2xl border-slate-200 text-sm py-3 px-4 focus:ring-cyan-500/20" 
                            :value="old('alamat')" placeholder="Masukkan alamat Anda" required />
                        <x-input-error :messages="$errors->get('alamat')" class="mt-1" />
                    </div>
                </div>

                <div>
                    <x-input-label for="content" :value="__('Isi Saran')" class="text-slate-900 font-bold mb-2 ml-1 text-xs uppercase tracking-widest" />
                    <textarea id="content" name="content" rows="3" 
                        class="w-full rounded-2xl border-slate-200 focus:border-cyan-500 focus:ring focus:ring-cyan-500/20 transition-all placeholder:text-slate-400 font-medium p-4 text-sm"
                        placeholder="Tuliskan saran atau masukan Anda di sini..." required>{{ old('content') }}</textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-1" />
                </div>

                <div class="pt-2">
                    <x-primary-button class="w-full bg-cyan-600 hover:bg-cyan-700 shadow-lg shadow-cyan-200 py-4 px-8 rounded-2xl justify-center font-black uppercase tracking-widest text-xs">
                        {{ __('Kirim Saran Sekarang') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
