<!-- Purchasing / Procurement Workspace (Pakaian Jadi & Apparel) -->
<div class="space-y-6">
    
    <!-- 1. Top Purchasing KPI Tiles -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- KPI 1: Estimasi Anggaran Reorder Dibutuhkan -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Estimasi Anggaran PO Pengadaan</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-navy-900 tracking-tight">
                    Rp {{ number_format($totalReorderBudget, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span>Untuk restock</span>
                    <strong class="text-rose-600">{{ $reorderSuggestions->count() }} SKU Apparel</strong>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Tingkat Urgensi:</span>
                <span class="font-bold {{ $criticalReorderCount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                    {{ $criticalReorderCount > 0 ? $criticalReorderCount . ' SKU Kritis' : 'Semua Buffer Aman' }}
                </span>
            </div>
        </div>

        <!-- KPI 2: SKU Perlu Order Ulang -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Kebutuhan Restock Produk</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black {{ $reorderSuggestions->count() > 0 ? 'text-rose-600' : 'text-emerald-600' }} tracking-tight">
                    {{ $reorderSuggestions->count() }} <span class="text-xs font-semibold text-slate-500">SKU Produk</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span class="font-bold text-rose-600">{{ $criticalReorderCount }}</span> Mendesak (Kritis) &bull;
                    <span class="font-bold text-amber-600">{{ $reorderSuggestions->count() - $criticalReorderCount }}</span> Menipis
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Analisis Detail:</span>
                <a href="{{ route('rbl.analisis') }}" class="font-bold text-orange-600 hover:underline">Matriks RBL &rarr;</a>
            </div>
        </div>

        <!-- KPI 3: Penerimaan Masuk Bulan Ini -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Realisasi PO Masuk (Bulan Ini)</span>
                <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-teal-700 tracking-tight">
                    +{{ number_format($inboundThisMonthQty) }} <span class="text-xs font-semibold text-slate-500">Pcs</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span class="font-bold text-teal-600">{{ $inboundThisMonthCount }}</span> Surat Jalan Diterima
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Riwayat Inbound:</span>
                <a href="{{ route('transaksi.masuk') }}" class="font-bold text-teal-700 hover:underline">Lihat Transaksi &rarr;</a>
            </div>
        </div>

        <!-- KPI 4: Pabrik / Vendor Konveksi Rekanan -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Vendor Konveksi / Pabrik</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-indigo-700 tracking-tight">
                    {{ $topSuppliers->count() }} <span class="text-xs font-semibold text-slate-500">Vendor</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span>Memproduksi <strong>{{ $totalSKU }} SKU</strong> apparel</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Total Valuasi:</span>
                <span class="font-bold text-navy-900">Rp {{ number_format($totalValuasi/1000000, 1) }} Juta</span>
            </div>
        </div>

    </div>

    <!-- 2. Interactive Multi-Period Financial & Procurement Control Center (Harian, Mingguan, Bulanan, Tahunan) -->
    <div class="p-5 sm:p-6 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-6"
         x-data="{
             activePeriod: 'bulanan',
             financials: {{ Js::from($purchasingFinancials) }},
             get current() {
                 return this.financials[this.activePeriod] || this.financials['bulanan'];
             },
             formatRupiah(num) {
                 return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
             }
         }">
        
        <!-- Header & Multi-Period Tab Switcher -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 text-white flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-navy-900">Ringkasan Finansial & Pendapatan Pengadaan</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Pantau realisasi belanja pengadaan ke vendor konveksi & valuasi perputaran distribusi pakaian jadi.</p>
                    </div>
                </div>
            </div>

            <!-- Periode Pill Switcher -->
            <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/70 self-start lg:self-auto shadow-3xs">
                <button type="button" 
                        @click="activePeriod = 'harian'" 
                        :class="activePeriod === 'harian' ? 'bg-white text-navy-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                    <span>📅 Harian</span>
                </button>
                <button type="button" 
                        @click="activePeriod = 'mingguan'" 
                        :class="activePeriod === 'mingguan' ? 'bg-white text-navy-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                    <span>📆 Mingguan</span>
                </button>
                <button type="button" 
                        @click="activePeriod = 'bulanan'" 
                        :class="activePeriod === 'bulanan' ? 'bg-white text-navy-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                    <span>🗓️ Bulanan</span>
                </button>
                <button type="button" 
                        @click="activePeriod = 'tahunan'" 
                        :class="activePeriod === 'tahunan' ? 'bg-white text-navy-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                    <span>📈 Tahunan</span>
                </button>
            </div>
        </div>

        <!-- Dynamic Selected Period Banner -->
        <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-orange-50/70 via-amber-50/40 to-slate-50 border border-orange-200/60">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-orange-600 text-white" x-text="current.badge"></span>
                <span class="text-xs font-bold text-navy-900">Periode Aktif:</span>
                <span class="text-xs font-semibold text-orange-800" x-text="current.period_label"></span>
            </div>
            <div class="text-[11px] text-slate-500 flex items-center gap-3">
                <span>Total PO/SJ Masuk: <strong class="text-navy-900" x-text="current.inbound_count + ' Transaksi'"></strong></span>
                <span>&bull;</span>
                <span>Total SPK/DO Keluar: <strong class="text-navy-900" x-text="current.outbound_count + ' Transaksi'"></strong></span>
            </div>
        </div>

        <!-- 4 Dynamic Financial KPI Cards for the Selected Period -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Belanja PO Masuk (Pengadaan) -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-orange-300 hover:shadow-xs transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Belanja PO Pengadaan</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Inbound (Masuk)
                    </span>
                </div>
                <div class="mt-2.5">
                    <div class="text-lg sm:text-xl font-black text-navy-900 tracking-tight" x-text="formatRupiah(current.inbound_nominal)">
                    </div>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-600 font-medium">
                        <span class="text-emerald-700 font-bold" x-text="'+' + Number(current.inbound_qty).toLocaleString('id-ID') + ' Pcs'"></span>
                        <span>dari vendor konveksi</span>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-200/80 flex items-center justify-between text-[10px] text-slate-500">
                    <span>Rata-rata / PO:</span>
                    <span class="font-bold text-slate-800" x-text="formatRupiah(current.avg_inbound)"></span>
                </div>
            </div>

            <!-- Card 2: Valuasi Distribusi Keluar (Pendapatan/Omzet Barang) -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-blue-300 hover:shadow-xs transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Pendapatan Distribusi Keluar</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                        Outbound (Keluar)
                    </span>
                </div>
                <div class="mt-2.5">
                    <div class="text-lg sm:text-xl font-black text-navy-900 tracking-tight" x-text="formatRupiah(current.outbound_nominal)">
                    </div>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-600 font-medium">
                        <span class="text-blue-700 font-bold" x-text="'-' + Number(current.outbound_qty).toLocaleString('id-ID') + ' Pcs'"></span>
                        <span>dikirim ke channel</span>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-200/80 flex items-center justify-between text-[10px] text-slate-500">
                    <span>Rata-rata / DO:</span>
                    <span class="font-bold text-slate-800" x-text="formatRupiah(current.avg_outbound)"></span>
                </div>
            </div>

            <!-- Card 3: Total Perputaran Finansial -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-amber-300 hover:shadow-xs transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Perputaran Nilai</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        Turnover Flow
                    </span>
                </div>
                <div class="mt-2.5">
                    <div class="text-lg sm:text-xl font-black text-navy-900 tracking-tight" x-text="formatRupiah(current.total_perputaran)">
                    </div>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-600 font-medium">
                        <span>Total Mutasi Fisik:</span>
                        <strong class="text-slate-800" x-text="Number(current.inbound_qty + current.outbound_qty).toLocaleString('id-ID') + ' Pcs'"></strong>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-200/80 flex items-center justify-between text-[10px] text-slate-500">
                    <span>Aktivitas Transaksi:</span>
                    <span class="font-bold text-slate-800" x-text="(current.inbound_count + current.outbound_count) + ' Dokumen'"></span>
                </div>
            </div>

            <!-- Card 4: Kebutuhan Buffer Anggaran RBL -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:bg-white hover:border-orange-300 hover:shadow-xs transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Estimasi Anggaran Reorder</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                        Proyeksi PO
                    </span>
                </div>
                <div class="mt-2.5">
                    <div class="text-lg sm:text-xl font-black text-orange-600 tracking-tight">
                        Rp {{ number_format($totalReorderBudget, 0, ',', '.') }}
                    </div>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-600 font-medium">
                        <span>Restock buffer:</span>
                        <strong class="text-rose-600">{{ $reorderSuggestions->count() }} SKU Kritis/Menipis</strong>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-200/80 flex items-center justify-between text-[10px]">
                    <span class="text-slate-500">Aksi Pengadaan:</span>
                    <a href="{{ route('rbl.analisis') }}" class="font-bold text-orange-600 hover:underline">Evaluasi PO &rarr;</a>
                </div>
            </div>

        </div>

        <!-- Multi-Period Side-by-Side Comparison Matrix -->
        <div class="pt-2">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-navy-900 uppercase tracking-wider text-slate-600">Komparasi Finansial Antar Periode (Harian, Mingguan, Bulanan, Tahunan)</h3>
                <a href="{{ route('laporan.index') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                    <span>Buka Laporan Lengkap</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-3">Periode Waktu</th>
                            <th class="p-3">Belanja PO (Masuk)</th>
                            <th class="p-3">Pendapatan / Valuasi Keluar</th>
                            <th class="p-3 text-center">Unit Masuk (Pcs)</th>
                            <th class="p-3 text-center">Unit Keluar (Pcs)</th>
                            <th class="p-3 text-center">Total Transaksi</th>
                            <th class="p-3 text-right">Perputaran Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                        @foreach(['harian' => '📅 Harian (Hari Ini)', 'mingguan' => '📆 Mingguan (Minggu Ini)', 'bulanan' => '🗓️ Bulanan (Bulan Ini)', 'tahunan' => '📈 Tahunan (Tahun Ini)'] as $pKey => $pTitle)
                            @php $item = $purchasingFinancials[$pKey] ?? []; @endphp
                            <tr class="hover:bg-orange-50/40 transition-colors cursor-pointer"
                                :class="activePeriod === '{{ $pKey }}' ? 'bg-orange-50/60 font-semibold' : ''"
                                @click="activePeriod = '{{ $pKey }}'">
                                <td class="p-3">
                                    <div class="font-bold text-navy-900 flex items-center gap-2">
                                        <span>{{ $pTitle }}</span>
                                        <span x-show="activePeriod === '{{ $pKey }}'" class="text-[10px] px-1.5 py-0.5 rounded bg-orange-500 text-white font-bold">Dipilih</span>
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-normal">{{ $item['period_label'] ?? '-' }}</div>
                                </td>
                                <td class="p-3 font-bold text-emerald-700">
                                    Rp {{ number_format($item['inbound_nominal'] ?? 0, 0, ',', '.') }}
                                    <div class="text-[10px] text-slate-400 font-normal">{{ $item['inbound_count'] ?? 0 }} Surat Jalan PO</div>
                                </td>
                                <td class="p-3 font-bold text-blue-700">
                                    Rp {{ number_format($item['outbound_nominal'] ?? 0, 0, ',', '.') }}
                                    <div class="text-[10px] text-slate-400 font-normal">{{ $item['outbound_count'] ?? 0 }} Transaksi Distribusi</div>
                                </td>
                                <td class="p-3 text-center font-bold text-slate-800">
                                    +{{ number_format($item['inbound_qty'] ?? 0) }}
                                </td>
                                <td class="p-3 text-center font-bold text-slate-800">
                                    -{{ number_format($item['outbound_qty'] ?? 0) }}
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ ($item['inbound_count'] ?? 0) + ($item['outbound_count'] ?? 0) }} Dokumen
                                    </span>
                                </td>
                                <td class="p-3 text-right font-black text-navy-900">
                                    Rp {{ number_format($item['total_perputaran'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- 3. Main Purchasing Section (Rekomendasi Reorder & Direktori Supplier) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Left: Tabel Rekomendasi Reorder Pengadaan Cerdas (2/3 width) -->
        <div class="lg:col-span-2 p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-bold text-navy-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span>Prioritas Restock / Purchase Order Pakaian Jadi (RBL Safety Buffer)</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Kalkulasi otomatis kebutuhan order produksi baru berdasarkan batas minimum dan lead time vendor.</p>
                </div>
                <a href="{{ route('rbl.analisis') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                    <span>Analisis RBL Lengkap</span>
                    <span>&rarr;</span>
                </a>
            </div>

            @if($reorderSuggestions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-slate-500 bg-slate-50 uppercase text-[10px] font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">SKU / Nama Produk</th>
                                <th class="p-2.5">Sisa Stok</th>
                                <th class="p-2.5">Status Buffer</th>
                                <th class="p-2.5">Saran Order</th>
                                <th class="p-2.5">Vendor Konveksi</th>
                                <th class="p-2.5">Estimasi Biaya</th>
                                <th class="p-2.5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($reorderSuggestions as $rec)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-2.5">
                                        <div class="font-bold text-navy-900">[{{ $rec['product']->kode_produk }}]</div>
                                        <div class="text-slate-600">{{ $rec['product']->nama }}</div>
                                    </td>
                                    <td class="p-2.5 font-bold {{ $rec['product']->status_stok === 'KRITIS' ? 'text-rose-600' : 'text-amber-700' }}">
                                        {{ $rec['product']->stok_aktual }} {{ $rec['product']->satuan }}
                                        <div class="text-[10px] text-slate-400 font-normal">Min: {{ $rec['product']->batas_minimum }}</div>
                                    </td>
                                    <td class="p-2.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $rec['product']->status_stok === 'KRITIS' ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                            {{ $rec['product']->status_stok }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 font-bold text-navy-900">
                                        +{{ $rec['suggested_qty'] }} {{ $rec['product']->satuan }}
                                    </td>
                                    <td class="p-2.5 text-slate-600">
                                        {{ $rec['product']->supplier_utama ?? '-' }}
                                        <div class="text-[10px] text-slate-400">Lead Time: {{ $rec['product']->lead_time_days }} hari</div>
                                    </td>
                                    <td class="p-2.5 text-navy-900 font-semibold">
                                        Rp {{ number_format($rec['estimated_cost'], 0, ',', '.') }}
                                    </td>
                                    <td class="p-2.5 text-right">
                                        <a href="{{ route('transaksi.masuk') }}" class="px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold text-[11px] inline-flex items-center gap-1 shadow-2xs">
                                            <span>+ PO Masuk</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Safe State Card -->
                <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-50/60 to-slate-50 border border-emerald-200/80 flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 text-2xl shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-emerald-900">Persediaan Pakaian Jadi Aman & Optimal!</h4>
                        <p class="text-xs text-slate-600 mt-0.5">Semua SKU produk (hoodie, kaos, jaket, kemeja, celana) saat ini berada di atas ambang batas kritis. Tidak ada purchase order mendesak yang perlu diterbitkan.</p>
                    </div>
                    <a href="{{ route('rbl.analisis') }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors shadow-2xs flex-shrink-0">
                        Evaluasi Matriks Buffer
                    </a>
                </div>
            @endif
        </div>

        <!-- Right: Direktori Vendor Konveksi & Lead Time (1/3 width) -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-sm font-bold text-navy-900">Daftar Vendor Konveksi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Lead time produksi dan cakupan pesanan</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                        {{ $topSuppliers->count() }} Mitra
                    </span>
                </div>

                <div class="space-y-2.5 max-h-96 overflow-y-auto custom-scrollbar pr-1">
                    @foreach($topSuppliers as $sup)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/70 transition-colors text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-navy-900">{{ $sup['supplier'] }}</span>
                                @if($sup['has_kritis'])
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-rose-100 text-rose-700">Perlu Order</span>
                                @else
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700">Stok Aman</span>
                                @endif
                            </div>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                                <span>Memproduksi: <strong>{{ $sup['sku_count'] }} SKU</strong></span>
                                <span>Rata-rata Lead Time: <strong>{{ $sup['avg_lead_time'] }} Hari</strong></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 text-center">
                <a href="{{ route('products.index') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700">
                    Buka Master Data & Vendor &rarr;
                </a>
            </div>
        </div>

    <!-- 4. Faktur & Nota Pembelian Terkini (Recent Purchase Notes & Invoices) -->
    <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-navy-900">Faktur & Nota Pembelian Barang Masuk Terkini</h3>
                    <p class="text-xs text-slate-500">Daftar transaksi pengadaan terakhir, harga beli, dan status pembayaran vendor</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('purchasing.nota.index') }}" class="px-3.5 py-1.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-semibold text-xs transition-colors flex items-center gap-1 shadow-2xs">
                    <span>Semua Nota Pembelian</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-3">No. Nota & Tanggal</th>
                        <th class="p-3">Vendor Konveksi</th>
                        <th class="p-3">Produk & Qty Masuk</th>
                        <th class="p-3">Harga Satuan</th>
                        <th class="p-3">Total Tagihan</th>
                        <th class="p-3 text-center">Status Bayar</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentPurchaseNotes ?? [] as $note)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="p-3">
                                <div class="font-mono font-bold text-navy-900">{{ $note->no_nota }}</div>
                                <div class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($note->tanggal)->format('d M Y') }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-bold text-slate-800">{{ $note->supplier ?: ($note->product->supplier_utama ?? 'Vendor') }}</div>
                                <div class="text-[10px] text-slate-400">SJ: {{ $note->no_surat_jalan ?: '-' }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-bold text-slate-900 truncate max-w-[180px]">{{ $note->product->nama ?? 'Produk' }}</div>
                                <div class="text-emerald-700 font-bold text-[11px]">+{{ number_format($note->jumlah) }} {{ $note->product->satuan ?? 'Pcs' }}</div>
                            </td>
                            <td class="p-3 font-medium text-slate-600">
                                Rp {{ number_format($note->harga_beli_satuan ?: ($note->product->harga_beli_per_satuan ?? 0), 0, ',', '.') }}
                            </td>
                            <td class="p-3 font-black text-navy-900">
                                Rp {{ number_format($note->total_harga ?: ($note->jumlah * ($note->harga_beli_satuan ?: ($note->product->harga_beli_per_satuan ?? 0))), 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center">
                                @if(($note->status_pembayaran ?? 'LUNAS') === 'LUNAS')
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        LUNAS
                                    </span>
                                @elseif(($note->status_pembayaran ?? '') === 'TEMPO')
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        TEMPO
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        DP
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <a href="{{ route('purchasing.nota.cetak', $note->id) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-700 font-semibold text-[11px] border border-orange-200 transition-colors inline-flex items-center gap-1" title="Cetak Nota">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Cetak</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400 text-xs">Belum ada nota pembelian yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
