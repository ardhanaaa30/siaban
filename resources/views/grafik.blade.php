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
                <!-- Filter Rentang Hari -->
                <form method="GET" action="{{ route('grafik') }}" class="flex items-center gap-4 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
                    <label for="days" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Rentang:</label>
                    <select name="days" id="days" onchange="this.form.submit()" class="bg-slate-50 border-none text-slate-900 text-sm font-bold rounded-xl focus:ring-2 focus:ring-cyan-500/20 block py-2 px-6">
                        <option value="1" {{ $days == 1 && !$month ? 'selected' : '' }}>24 Jam</option>
                        <option value="7" {{ $days == 7 && !$month ? 'selected' : '' }}>7 Hari</option>
                        <option value="30" {{ $days == 30 && !$month ? 'selected' : '' }}>30 Hari</option>
                    </select>
                </form>

                <!-- Filter Bulan -->
                <form method="GET" action="{{ route('grafik') }}" class="flex items-center gap-4 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
                    <label for="month" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-4">Bulan:</label>
                    <select name="month" id="month" class="bg-slate-50 border-none text-slate-900 text-sm font-bold rounded-xl focus:ring-2 focus:ring-cyan-500/20 block py-2 px-4">
                        <option value="">Pilih Bulan</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="year" id="year" class="bg-slate-50 border-none text-slate-900 text-sm font-bold rounded-xl focus:ring-2 focus:ring-cyan-500/20 block py-2 px-4">
                        @for ($y = Carbon\Carbon::now()->year; $y >= Carbon\Carbon::now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-cyan-600 text-white p-2.5 rounded-xl hover:bg-cyan-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    @if($month)
                        <a href="{{ route('grafik') }}" class="bg-slate-100 text-slate-500 p-2.5 rounded-xl hover:bg-slate-200 transition-colors" title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
        @if(count($chartData) > 0)
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
                <a href="{{ route('grafik') }}" class="mt-8 text-cyan-600 font-bold hover:text-cyan-700 transition-colors uppercase tracking-widest text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke 7 Hari Terakhir
                </a>
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
                        pointBackgroundColor: '#0891b2',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: dataCount > 100 ? 0 : 4,
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
