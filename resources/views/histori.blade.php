<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Histori Data') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Telusuri riwayat pembacaan sensor dan status.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Filter Card -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
            <form method="GET" action="{{ route('histori') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 items-end">
                <div class="space-y-2">
                    <label for="date" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Filter Tanggal</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" class="w-full bg-slate-50 border-none text-slate-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-cyan-500/20 py-3 px-5 transition-all">
                </div>
                
                <div class="space-y-2">
                    <label for="status" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Filter Status</label>
                    <select name="status" id="status" class="w-full bg-slate-50 border-none text-slate-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-cyan-500/20 py-3 px-5 transition-all">
                        <option value="">Semua Status</option>
                        <option value="Aman" {{ request('status') == 'Aman' ? 'selected' : '' }}>Aman</option>
                        <option value="Siaga" {{ request('status') == 'Siaga' ? 'selected' : '' }}>Siaga</option>
                        <option value="Bahaya" {{ request('status') == 'Bahaya' ? 'selected' : '' }}>Bahaya</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-cyan-600 text-white font-black text-sm py-3.5 px-6 rounded-2xl hover:bg-cyan-700 transition-all shadow-lg shadow-cyan-200 uppercase tracking-widest">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['date', 'status']))
                    <a href="{{ route('histori') }}" class="bg-slate-100 text-slate-600 font-black text-sm py-3.5 px-6 rounded-2xl hover:bg-slate-200 transition-all uppercase tracking-widest">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">No.</th>
                            <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Waktu (WIB)</th>
                            <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Tinggi Air (m)</th>
                            <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Status Prediksi</th>
                            <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Confidence</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($readings as $index => $reading)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 text-sm font-bold text-slate-400">
                                    {{ $readings->firstItem() + $index }}
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900">{{ $reading->datetime->format('d M Y') }}</span>
                                        <span class="text-xs font-bold text-slate-400 mt-0.5 uppercase tracking-wider">{{ $reading->datetime->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-black text-slate-900">{{ number_format($reading->tinggi_air, 2) }}</span>
                                        <span class="text-xs font-bold text-slate-400">m</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $badgeColor = 'emerald';
                                        if ($reading->status_prediksi === 'Siaga') $badgeColor = 'amber';
                                        if ($reading->status_prediksi === 'Bahaya') $badgeColor = 'rose';
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-xs font-black bg-{{ $badgeColor }}-50 text-{{ $badgeColor }}-700 border border-{{ $badgeColor }}-100 uppercase tracking-widest">
                                        {{ $reading->status_prediksi ?? 'Aman' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-sm font-black text-slate-700">
                                    {{ number_format($reading->confidence * 100, 1) }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Data tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($readings->hasPages())
                <div class="p-8 bg-slate-50/30 border-t border-slate-50">
                    {{ $readings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
