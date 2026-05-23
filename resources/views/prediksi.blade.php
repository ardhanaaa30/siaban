<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Prediksi Banjir') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Jalankan analisis AI menggunakan model LSTM.</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Prediksi -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="p-3 bg-cyan-50 rounded-2xl">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900">Input Data Analisis</h3>
            </div>
            
            <p class="text-sm text-slate-500 font-medium mb-8 leading-relaxed">
                Masukkan nilai tinggi air secara manual atau biarkan sistem mengambil sequence data terbaru dari sensor untuk dianalisis oleh model AI.
            </p>

            <form action="{{ route('prediksi.proses') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-3">
                    <label for="tinggi_air" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Tinggi Air Manual (m)</label>
                    <input type="number" step="0.01" name="tinggi_air" id="tinggi_air" class="w-full bg-slate-50 border-none text-slate-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-cyan-500/20 py-4 px-6 transition-all" placeholder="Contoh: 2.15">
                    <p class="text-[11px] text-slate-400 font-bold italic ml-1">* Kosongkan untuk menggunakan 24 data sensor terakhir secara otomatis.</p>
                </div>

                <button type="submit" class="w-full bg-cyan-600 text-white font-black text-sm py-4 rounded-2xl hover:bg-cyan-700 transition-all shadow-lg shadow-cyan-200 uppercase tracking-widest flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Jalankan Prediksi AI
                </button>
            </form>

            @if(session('success') || session('error'))
                <div class="mt-8 p-6 rounded-2xl {{ session('success') ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div class="text-sm font-bold leading-relaxed">
                            {!! session('success') ?? session('error') !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- 24 Data Terakhir Overview -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-10 border-b border-slate-50">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-indigo-50 rounded-2xl">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Sequence Input</h3>
                </div>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">
                    24 data terbaru yang digunakan sebagai basis analisis model LSTM.
                </p>
            </div>

            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Waktu</th>
                            <th class="px-10 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Tinggi (m)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($latestReadings as $r)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-10 py-4 text-sm font-bold text-slate-600">{{ $r->datetime->format('H:i:s') }}</td>
                            <td class="px-10 py-4 text-right">
                                <span class="text-base font-black text-slate-900">{{ number_format($r->tinggi_air, 2) }}</span>
                                <span class="text-xs font-bold text-slate-400 ml-1">m</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-10 py-20 text-center">
                                <p class="text-slate-300 font-bold uppercase tracking-widest text-xs">Belum ada data</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
