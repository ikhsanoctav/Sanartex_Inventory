@extends('layouts.app')

@section('title', 'Dashboard ' . $user->role_label)

@section('content')
<div class="space-y-6">
    
    <!-- 1. Dynamic Role Header -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $user->role_badge_class }}">
                    <span>{{ $user->role_label }}</span>
                </span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs font-semibold text-slate-500">PT SANARTEX INDONESIA</span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="inline-flex items-center gap-1 text-[11px] text-emerald-600 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Monitoring Real-time RBL Buffer</span>
                </span>
            </div>
            
            <h1 class="text-xl sm:text-2xl font-black text-navy-900 tracking-tight">
                Selamat Datang, {{ $user->name }}
            </h1>
            
            <p class="text-xs text-slate-500 mt-1">
                @if($user->role === 'manajemen')
                    Dashboard Intelijen Bisnis C-Level: Monitoring posisi likuiditas persediaan, valuasi aset pakaian jadi, dan evaluasi pengadaan strategis.
                @elseif($user->role === 'purchasing')
                    Pusat pemantauan reorder barang jadi apparel, purchase order pengadaan, koordinasi vendor konveksi, dan buffer level RBL.
                @elseif($user->role === 'admin_gudang')
                    Pusat operasional pencatatan cepat barang masuk konveksi (+IN), pengeluaran pesanan outlet/marketplace (-OUT), dan lokasi rak fisik.
                @elseif($user->role === 'kepala_gudang')
                    Pusat manajerial pengawasan buffer stok pakaian jadi, kapasitas simpan gudang, zonasi rak, dan pencegahan overstock/kritis.
                @else
                    Overview eksekutif operasional gudang apparel, valuasi stok fisik pakaian jadi, serta analisis cerdas buffer level RBL.
                @endif
            </p>
        </div>

        <!-- Role-Specific Action Buttons -->
        <div class="flex flex-wrap items-center gap-2">
            @if($user->role === 'manajemen')
                <a href="{{ route('laporan.index') }}" class="px-3.5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-all shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Laporan Valuasi</span>
                </a>
                <a href="{{ route('purchasing.nota.index') }}" class="px-3.5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Nota Pembelian</span>
                </a>
                <a href="{{ route('rbl.analisis') }}" class="px-3.5 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-950 text-white font-semibold text-xs transition-colors flex items-center gap-1.5 shadow-xs">
                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Matriks RBL</span>
                </a>
            @elseif($user->role === 'purchasing')
                <a href="{{ route('transaksi.masuk') }}" class="px-3.5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Proses PO Masuk</span>
                </a>
                <a href="{{ route('rbl.analisis') }}" class="px-3.5 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-950 text-white font-semibold text-xs transition-colors flex items-center gap-1.5 shadow-xs">
                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Analisis Reorder RBL</span>
                </a>
            @elseif($user->role === 'admin_gudang')
                <a href="{{ route('transaksi.masuk') }}" class="px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-all shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Stok Masuk</span>
                </a>
                <a href="{{ route('transaksi.keluar') }}" class="px-3.5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    <span>Catat Stok Keluar</span>
                </a>
                <button 
                    type="button" 
                    @click="window.triggerBarcodeScan({ title: 'Scan Barcode Cepat (SKU Apparel)', callback: (code) => { const el = document.getElementById('topbar_search_input'); if(el){ el.value = code; document.getElementById('topbar_search_form').submit(); } } })"
                    class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Scan Barcode</span>
                </button>
            @elseif($user->role === 'kepala_gudang')
                <a href="{{ route('rbl.analisis') }}" class="px-3.5 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-950 text-white font-semibold text-xs transition-colors flex items-center gap-1.5 shadow-xs">
                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Matriks Buffer RBL</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors flex items-center gap-1.5 shadow-xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Laporan Mutasi</span>
                </a>
            @else
                <!-- Superadmin default buttons -->
                <a href="{{ route('transaksi.masuk') }}" class="px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-all shadow-xs flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Masuk</span>
                </a>
                <a href="{{ route('transaksi.keluar') }}" class="px-3 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all shadow-xs flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    <span>Keluar</span>
                </a>
                <a href="{{ route('rbl.analisis') }}" class="px-3 py-2 rounded-xl bg-navy-900 hover:bg-navy-950 text-white font-semibold text-xs transition-colors flex items-center gap-1.5 shadow-xs">
                    <svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>RBL</span>
                </a>
            @endif
        </div>
    </div>

    <!-- 2. Role Workspace Content -->
    @if($user->role === 'manajemen')
        @include('dashboard.partials.manajemen')
    @elseif($user->role === 'purchasing')
        @include('dashboard.partials.purchasing')
    @elseif($user->role === 'admin_gudang')
        @include('dashboard.partials.admin_gudang')
    @elseif($user->role === 'kepala_gudang')
        @include('dashboard.partials.kepala_gudang')
    @else
        @include('dashboard.partials.superadmin')
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.allTrendDatasets = @json($trendDatasets);

    window.switchTrendRange = function(rangeKey) {
        const trendCtx = document.getElementById('mutationTrendChart');
        if (!trendCtx || !trendCtx.chartInstance) return;
        
        const data = window.allTrendDatasets[rangeKey];
        if (!data) return;

        trendCtx.chartInstance.data.labels = data.labels;
        trendCtx.chartInstance.data.datasets[0].data = data.inbound;
        trendCtx.chartInstance.data.datasets[1].data = data.outbound;
        trendCtx.chartInstance.update('active');
    };

    function initDashboardCharts() {
        // 1. Chart Tren Mutasi Multi-Periode (Harian, Mingguan, Triwulan, Semester, Bulanan)
        const trendCtx = document.getElementById('mutationTrendChart');
        if (trendCtx && !trendCtx.chartInstance) {
            const initialData = window.allTrendDatasets['semester'] || {
                labels: @json($monthsLabels),
                inbound: @json($chartInbound),
                outbound: @json($chartOutbound)
            };

            trendCtx.chartInstance = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: initialData.labels,
                    datasets: [
                        {
                            label: 'Stok Masuk (+IN)',
                            data: initialData.inbound,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.12)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Stok Keluar (-OUT)',
                            data: initialData.outbound,
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#f97316',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f243f',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID') + ' Pcs';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, color: '#64748b' }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: { 
                                font: { size: 11 }, 
                                color: '#64748b',
                                callback: function(value) { return value.toLocaleString('id-ID') + ' Pcs'; }
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // 2. Chart Komposisi Kategori Pakaian Jadi (Donut Chart)
        const donutCtx = document.getElementById('categoryDonutChart');
        if (donutCtx && !donutCtx.chartInstance) {
            const catData = @json($categoryBreakdown);
            const catLabels = catData.map(c => c.name);
            const catValues = catData.map(c => c.valuation);
            const colors = ['#0f243f', '#f97316', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899', '#f59e0b', '#64748b'];

            donutCtx.chartInstance = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catValues,
                        backgroundColor: colors.slice(0, catLabels.length),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f243f',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const val = context.parsed;
                                    return context.label + ': Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    initDashboardCharts();

    // Re-init charts when Alpine switches workspace
    document.addEventListener('alpine:initialized', () => {
        setTimeout(initDashboardCharts, 100);
    });
});
</script>
@endpush
