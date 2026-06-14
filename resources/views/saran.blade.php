<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Daftar Saran Warga') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Kumpulan saran dan masukan dari warga untuk lingkungan yang lebih baik.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- List Section -->
        <div class="space-y-6">
            <h3 class="text-2xl font-black text-slate-900 ml-2">Saran Masuk</h3>
            
            @forelse ($suggestions as $suggestion)
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 transition-all hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $suggestion->nama ?? $suggestion->user?->name ?? 'Anonim' }}</h4>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    {{ $suggestion->alamat ?? 'Alamat tidak diketahui' }} • {{ $suggestion->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @if($suggestion->user)
                            <span class="px-3 py-1 bg-cyan-50 text-cyan-600 text-xs font-bold rounded-full uppercase tracking-wider">
                                {{ $suggestion->user->role }}
                            </span>
                        @endif
                    </div>
                    <div class="mt-6 text-slate-600 leading-relaxed font-medium">
                        {{ $suggestion->content }}
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2rem] p-12 shadow-sm border border-slate-100 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <h4 class="text-slate-400 font-bold uppercase tracking-widest text-sm">Belum ada saran yang dikirim</h4>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
