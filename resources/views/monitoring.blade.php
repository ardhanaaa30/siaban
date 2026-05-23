<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Monitoring Sistem') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Data sensor realtime dari lapangan.</p>
            </div>
            <button onclick="window.location.reload()" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all shadow-sm">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Data
            </button>
        </div>
    </x-slot>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Waktu Sensor</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Tinggi Air (m)</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Status Prediksi</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Confidence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($readings as $reading)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900">{{ $reading->datetime->format('d M Y') }}</span>
                                    <span class="text-xs font-bold text-slate-400 mt-0.5 uppercase tracking-wider">{{ $reading->datetime->format('H:i:s') }} WIB</span>
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
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-2 bg-slate-100 rounded-full max-w-[100px] overflow-hidden">
                                        <div class="h-full bg-cyan-500 rounded-full" style="width: {{ $reading->confidence * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-black text-slate-700">{{ number_format($reading->confidence * 100, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Tidak ada data sensor</p>
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
</x-app-layout>
