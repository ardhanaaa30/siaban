<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Ringkasan Monitoring') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Pantau ketinggian air dan prediksi banjir secara realtime.</p>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-2xl border border-emerald-100">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Sistem Aktif
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Current Water Level -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:shadow-slate-200/50 group">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tinggi Air</p>
                    <div class="p-2 bg-cyan-50 rounded-xl group-hover:bg-cyan-600 transition-colors">
                        <svg class="w-5 h-5 text-cyan-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-5xl font-black text-slate-900">
                        {{ $latestReading ? number_format($latestReading->tinggi_air, 2) : '0.00' }}
                    </h3>
                    <span class="text-xl font-bold text-slate-400 mb-1">meter</span>
                </div>
                <p class="text-xs font-bold text-slate-400 mt-6 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $latestReading ? $latestReading->datetime->diffForHumans() : '-' }}
                </p>
            </div>

            <!-- Current Status -->
            @php
                $statusColor = 'emerald';
                $statusText = 'Aman';
                if ($latestReading) {
                    $statusText = $latestReading->status_prediksi;
                    if ($statusText === 'Siaga') $statusColor = 'amber';
                    if ($statusText === 'Bahaya') $statusColor = 'rose';
                }
            @endphp
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:shadow-slate-200/50 group">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <div class="p-2 bg-{{ $statusColor }}-50 rounded-xl group-hover:bg-{{ $statusColor }}-600 transition-colors">
                        <svg class="w-5 h-5 text-{{ $statusColor }}-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="inline-flex items-center px-6 py-2.5 rounded-2xl bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 font-black text-2xl border border-{{ $statusColor }}-100">
                    {{ $statusText }}
                </div>
                <p class="text-xs font-bold text-slate-400 mt-6 uppercase tracking-tight">Kondisi berdasarkan sensor</p>
            </div>

            <!-- Highest Today -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:shadow-slate-200/50 group">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Puncak Hari Ini</p>
                    <div class="p-2 bg-indigo-50 rounded-xl group-hover:bg-indigo-600 transition-colors">
                        <svg class="w-5 h-5 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
                <h3 class="text-5xl font-black text-slate-900">
                    {{ number_format($highestToday ?? 0, 2) }} <span class="text-xl font-bold text-slate-400">m</span>
                </h3>
                <p class="text-xs font-bold text-slate-400 mt-6 uppercase tracking-tight">Ketinggian maksimal hari ini</p>
            </div>

            <!-- Alert Count -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:shadow-slate-200/50 group">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total Peringatan</p>
                    <div class="p-2 bg-orange-50 rounded-xl group-hover:bg-orange-600 transition-colors">
                        <svg class="w-5 h-5 text-orange-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                </div>
                <h3 class="text-5xl font-black text-slate-900">
                    {{ $alertCount }}
                </h3>
                <p class="text-xs font-bold text-slate-400 mt-6 uppercase tracking-tight">Kejadian Siaga & Bahaya</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Chart Card -->
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Tren Ketinggian Air</h3>
                        <p class="text-slate-500 font-medium text-sm mt-1">Data fluktuasi air dalam 24 jam terakhir</p>
                    </div>
                    <select class="text-sm font-bold border-none bg-slate-50 rounded-xl focus:ring-2 focus:ring-cyan-500/20 py-2.5 px-5">
                        <option>Hari Ini</option>
                    </select>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="waterChart"></canvas>
                </div>
            </div>

            <!-- Prediction Result -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 flex flex-col">
                <h3 class="text-2xl font-black text-slate-900 mb-10">Hasil Prediksi AI</h3>
                
                @if($latestPrediction)
                    @php
                        $predColor = 'emerald';
                        if ($latestPrediction->prediction === 'Siaga') $predColor = 'amber';
                        if ($latestPrediction->prediction === 'Bahaya') $predColor = 'rose';
                    @endphp
                    <div class="flex-1 flex flex-col items-center justify-center p-10 rounded-[2rem] bg-{{ $predColor }}-50/50 border-2 border-dashed border-{{ $predColor }}-200">
                        <div class="w-24 h-24 rounded-3xl bg-white shadow-xl shadow-{{ $predColor }}-200/50 flex items-center justify-center mb-8 rotate-3 transition-transform hover:rotate-0">
                            <svg class="w-12 h-12 text-{{ $predColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="text-4xl font-black text-{{ $predColor }}-700 mb-4">{{ $latestPrediction->prediction }}</h4>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-black text-{{ $predColor }}-600/60 uppercase tracking-[0.2em]">Akurasi</span>
                            @php
                                $probs = is_array($latestPrediction->probabilities) 
                                    ? $latestPrediction->probabilities 
                                    : json_decode($latestPrediction->probabilities, true);
                                    
                                // For consistency with Prediction page, use the confidence from API result if available
                                $accuracy = isset($latestPrediction->confidence) 
                                    ? $latestPrediction->confidence * 100
                                    : (!empty($probs) ? max($probs) * 100 : 0);
                            @endphp
                            <span class="px-4 py-1.5 bg-white rounded-xl text-sm font-black text-slate-900 shadow-sm border border-{{ $predColor }}-100">{{ number_format($accuracy, 1) }}%</span>
                        </div>
                    </div>
                    <div class="mt-10 flex items-center justify-between px-2">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Update Terakhir</p>
                            <p class="text-sm font-bold text-slate-700">{{ $latestPrediction->created_at->format('H:i') }} WIB</p>
                        </div>
                        <a href="{{ route('prediksi') }}" class="p-3 bg-slate-50 text-slate-400 rounded-xl hover:bg-cyan-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-300 italic p-10">
                        <div class="w-20 h-20 rounded-full border-4 border-dashed border-slate-100 mb-6"></div>
                        <p class="font-bold uppercase tracking-widest text-xs">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-suggestion-form />

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('waterChart').getContext('2d');
            const gridColor = '#f1f5f9';
            
            // Create Gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(8, 145, 178, 0.15)');
            gradient.addColorStop(1, 'rgba(8, 145, 178, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Ketinggian (m)',
                        data: @json($chartData),
                        borderColor: '#0891b2',
                        borderWidth: 4,
                        fill: true,
                        backgroundColor: gradient,
                        tension: 0.4,
                        pointBackgroundColor: '#0891b2',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#0891b2',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#0f172a',
                            bodyColor: '#64748b',
                            borderColor: '#f1f5f9',
                            borderWidth: 1,
                            padding: 16,
                            displayColors: false,
                            boxPadding: 8,
                            titleFont: { size: 14, weight: '800' },
                            bodyFont: { size: 14, weight: '600' },
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' meter';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { 
                                color: '#94a3b8',
                                font: { size: 11, weight: '700' },
                                padding: 10
                            }
                        },
                        y: {
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { 
                                color: '#94a3b8',
                                font: { size: 11, weight: '700' },
                                padding: 15
                            },
                            beginAtZero: true,
                            suggestedMax: 3
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
