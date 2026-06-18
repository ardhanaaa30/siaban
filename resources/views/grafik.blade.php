<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Grafik Tinggi Air') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Visualisasi data {{ $title }}.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('grafik') }}" class="flex flex-wrap items-center gap-4 bg-white p-3 rounded-[1.5rem] border border-slate-100 shadow-md shadow-slate-100/50">
                    <input type="hidden" name="search" value="1">
                    
                    <!-- Filter Rentang -->
                    <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100/50 hover:bg-slate-100/40 transition-colors">
                        <div class="text-cyan-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Rentang Waktu</span>
                            <div class="relative flex items-center mt-0.5">
                                <select name="days" id="days" class="appearance-none bg-transparent bg-none border-none text-slate-800 text-xs font-black p-0 pr-6 focus:ring-0 cursor-pointer">
                                    <option value="">Pilih Rentang</option>
                                    <option value="1" {{ $days == 1 ? 'selected' : '' }}>24 Jam Terakhir</option>
                                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
                                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                                </select>
                                <div class="pointer-events-none absolute right-0 text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hidden xl:block h-8 w-px bg-slate-200/60 mx-1"></div>

                    <!-- Filter Bulan -->
                    <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100/50 hover:bg-slate-100/40 transition-colors">
                        <div class="text-cyan-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Bulan</span>
                            <div class="relative flex items-center mt-0.5">
                                <select name="month" id="month" class="appearance-none bg-transparent bg-none border-none text-slate-800 text-xs font-black p-0 pr-6 focus:ring-0 cursor-pointer">
                                    <option value="">Pilih Bulan</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                                <div class="pointer-events-none absolute right-0 text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Tahun -->
                    <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100/50 hover:bg-slate-100/40 transition-colors">
                        <div class="text-cyan-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Tahun</span>
                            <div class="relative flex items-center mt-0.5">
                                <select name="year" id="year" class="appearance-none bg-transparent bg-none border-none text-slate-800 text-xs font-black p-0 pr-6 focus:ring-0 cursor-pointer">
                                    @for ($y = Carbon\Carbon::now()->year; $y >= Carbon\Carbon::now()->year - 2; $y--)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <div class="pointer-events-none absolute right-0 text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 ml-auto">
                        <button type="submit" class="bg-cyan-600 text-white px-6 py-3 rounded-2xl hover:bg-cyan-700 transition-all font-black text-sm shadow-md shadow-cyan-200/50 hover:shadow-cyan-300/60 active:scale-[0.98] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari Data
                        </button>

                        @if($isSearching)
                            <a href="{{ route('grafik') }}" class="bg-slate-100 text-slate-500 p-3.5 rounded-2xl hover:bg-slate-200 hover:text-slate-800 transition-all active:scale-[0.98]" title="Reset Filter">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
        @if($isSearching)
            @if(count($chartData) > 0)
                <!-- Indikator Debit Air / Tinggi Air (Ke Atas / Atas Grafik) -->
                @if($latestReading)
                    @php
                        $statusColor = 'emerald';
                        $statusText = 'Aman';
                        if ($latestReading->status_prediksi === 'Siaga') {
                            $statusColor = 'amber';
                            $statusText = 'Siaga';
                        } elseif ($latestReading->status_prediksi === 'Bahaya') {
                            $statusColor = 'rose';
                            $statusText = 'Bahaya';
                        }
                        
                        // Trend color determination
                        $trendColor = 'slate';
                        $trendBg = 'bg-slate-50';
                        $trendText = 'Stabil';
                        $trendIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>';
                        
                        if ($trend === 'up') {
                            $trendColor = 'rose'; // Rising water is warning/danger
                            $trendBg = 'bg-rose-50';
                            $trendText = 'Meningkat (Naik)';
                            $trendIcon = '<svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>';
                        } elseif ($trend === 'down') {
                            $trendColor = 'emerald'; // Falling water is safe/good
                            $trendBg = 'bg-emerald-50';
                            $trendText = 'Menurun (Turun)';
                            $trendIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>';
                        }
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <!-- Card 1: Tinggi / Debit Air -->
                        <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100 flex items-center gap-5 transition-all hover:shadow-md hover:bg-white">
                            <div class="p-4 bg-{{ $statusColor }}-50 rounded-2xl text-{{ $statusColor }}-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Debit / Tinggi Air</p>
                                <div class="flex items-baseline gap-1.5 mt-1">
                                    <span class="text-3xl font-black text-slate-900">{{ number_format($latestReading->tinggi_air, 2) }}</span>
                                    <span class="text-sm font-bold text-slate-500">m</span>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 mt-2 rounded-lg text-[10px] font-black bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 border border-{{ $statusColor }}-100 uppercase tracking-wider">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>

                        <!-- Card 2: Tren Perubahan -->
                        <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100 flex items-center gap-5 transition-all hover:shadow-md hover:bg-white">
                            <div class="p-4 {{ $trendBg }} rounded-2xl text-{{ $trendColor }}-600">
                                {!! $trendIcon !!}
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tren Debit Air</p>
                                <div class="flex items-baseline gap-1.5 mt-1">
                                    <span class="text-lg font-black text-slate-900">{{ $trendText }}</span>
                                </div>
                                <p class="text-xs font-bold text-slate-400 mt-2">
                                    @if($trend === 'up')
                                        Meningkat <span class="text-rose-600 font-extrabold">+{{ number_format($diff, 2) }} m</span> dari data sebelumnya
                                    @elseif($trend === 'down')
                                        Menurun <span class="text-emerald-600 font-extrabold">{{ number_format($diff, 2) }} m</span> dari data sebelumnya
                                    @else
                                        Stabil / tidak ada perubahan
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Card 3: Waktu Pembacaan -->
                        <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100 flex items-center gap-5 transition-all hover:shadow-md hover:bg-white">
                            <div class="p-4 bg-blue-50 rounded-2xl text-blue-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Waktu Sensor</p>
                                <p class="text-lg font-black text-slate-900 mt-1 leading-tight">{{ $latestReading->datetime->format('H:i:s') }} <span class="text-xs font-bold text-slate-500">WIB</span></p>
                                <p class="text-xs font-bold text-slate-400 mt-1.5 flex items-center gap-1">
                                    {{ $latestReading->datetime->format('d M Y') }}
                                    <span class="inline-block w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                    {{ $latestReading->datetime->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="relative h-[65vh] w-full">
                    <canvas id="mainWaterChart"></canvas>
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-[65vh] text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">Tidak Ada Data</h3>
                    <p class="text-slate-500 font-medium">Data sensor tidak ditemukan untuk periode {{ $title }}.</p>
                </div>
            @endif
        @else
            <div class="flex flex-col items-center justify-center h-[65vh] text-center">
                <div class="w-24 h-24 bg-cyan-50 rounded-full flex items-center justify-center mb-8">
                    <svg class="w-12 h-12 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-3">Siap Menampilkan Grafik</h3>
                <p class="text-slate-500 font-medium max-w-sm mx-auto">Silakan pilih rentang waktu atau bulan tertentu pada filter di atas untuk melihat visualisasi tinggi air.</p>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById('mainWaterChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const gridColor = '#f1f5f9';
            
            // Create Gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 600);
            gradient.addColorStop(0, 'rgba(8, 145, 178, 0.1)');
            gradient.addColorStop(1, 'rgba(8, 145, 178, 0)');

            const dataCount = @json(count($chartData));
            const statuses = @json($chartStatuses ?? []);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Tinggi Air (m)',
                        data: @json($chartData),
                        borderColor: '#0891b2',
                        backgroundColor: gradient,
                        borderWidth: dataCount > 500 ? 2 : 4,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: function(context) {
                            const index = context.dataIndex;
                            const status = statuses[index];
                            if (status === 'Bahaya') return '#f43f5e'; // rose-500
                            if (status === 'Siaga') return '#f59e0b'; // amber-500
                            return '#10b981'; // emerald-500
                        },
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: dataCount > 100 ? function(context) {
                            const index = context.dataIndex;
                            const status = statuses[index];
                            return (status === 'Bahaya' || status === 'Siaga') ? 5 : 0;
                        } : 5,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: function(context) {
                            const index = context.dataIndex;
                            const status = statuses[index];
                            if (status === 'Bahaya') return '#f43f5e';
                            if (status === 'Siaga') return '#f59e0b';
                            return '#10b981';
                        },
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
                                    const index = context.dataIndex;
                                    const status = statuses[index] || 'Aman';
                                    return [
                                        `Tinggi Air: ${context.parsed.y} meter`,
                                        `Status: ${status}`
                                    ];
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
                                padding: 10,
                                maxTicksLimit: dataCount > 1000 ? 8 : 12,
                                autoSkip: true
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
                            suggestedMax: 6
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
