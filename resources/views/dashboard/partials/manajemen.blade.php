<!-- Direksi / Manajemen Eksekutif C-Level BI Dashboard -->
<div class="space-y-6">
    
    <!-- 1. Executive Executive Summary Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-navy-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-20 top-0 w-48 h-48 bg-orange-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-sm flex items-center gap-1.5">
                        <span>🏢 C-Level Executive Dashboard</span>
                    </span>
                    <span class="text-xs text-slate-400">&bull;</span>
                    <span class="text-xs font-mono text-indigo-300">Posisi Likuiditas & Aset Persediaan</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    Ringkasan Strategis Manajemen & Valuasi Persediaan
                </h2>
                <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                    Pantau kinerja perputaran aset pakaian jadi (*Inventory Turnover*), kebutuhan alokasi modal kerja (*Working Capital*), dan evaluasi efisiensi pengadaan secara komprehensif.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('laporan.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition border border-white/15 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Laporan Mutasi Lengkap</span>
                </a>
                <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs transition shadow-lg shadow-orange-500/25 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Ringkasan Eksekutif</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Top Executive Financial & Asset Health KPIs (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- KPI 1: Total Valuasi Persediaan Fisik -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Valuasi Aset Persediaan</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-navy-900 tracking-tight">
                    Rp {{ number_format($totalValuasi, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-2 mt-1.5 text-[11px] text-slate-500">
                    <span><strong>{{ number_format($totalStokFisik) }}</strong> Pcs Barang Jadi</span>
                    <span class="text-slate-300">&bull;</span>
                    <span><strong>{{ $totalSKU }}</strong> SKU</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Status Aset:</span>
                <span class="font-bold text-emerald-700">Aktif Terkelola</span>
            </div>
        </div>

        <!-- KPI 2: Total Belanja Pengadaan (Tahun Berjalan) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Total Pengadaan (Tahun Berjalan)</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-blue-900 tracking-tight">
                    Rp {{ number_format($purchasingFinancials['tahunan']['total_belanja'] ?? 0, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-2 mt-1.5 text-[11px] text-slate-500">
                    <span><strong>{{ $purchasingFinancials['tahunan']['total_nota'] ?? 0 }}</strong> Faktur Nota Diterbitkan</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Bulan Ini:</span>
                <span class="font-bold text-slate-800">Rp {{ number_format($purchasingFinancials['bulanan']['total_belanja'] ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- KPI 3: Kewajiban Hutang Dagang (Tempo) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Kewajiban Hutang Usaha (Tempo)</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-amber-700 tracking-tight">
                    Rp {{ number_format($purchasingFinancials['tahunan']['total_tempo'] ?? 0, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-2 mt-1.5 text-[11px] text-slate-500">
                    <span><strong>{{ $purchasingFinancials['tahunan']['count_tempo'] ?? 0 }}</strong> Nota Menunggu Pelunasan</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Total Lunas:</span>
                <span class="font-bold text-emerald-700">Rp {{ number_format($purchasingFinancials['tahunan']['total_lunas'] ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- KPI 4: Indeks Kesehatan Buffer RBL -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Indeks Efisiensi Buffer RBL</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="flex items-baseline gap-2">
                    <div class="text-xl sm:text-2xl font-black text-emerald-600 tracking-tight">
                        {{ $healthScore }}%
                    </div>
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">OPTIMAL</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden flex">
                    <div class="bg-emerald-500 h-2" style="width: {{ $healthScore }}%"></div>
                    <div class="bg-rose-500 h-2" style="width: {{ $totalSKU > 0 ? ($kritisCount / $totalSKU)*100 : 0 }}%"></div>
                    <div class="bg-blue-500 h-2" style="width: {{ $totalSKU > 0 ? ($berlebihCount / $totalSKU)*100 : 0 }}%"></div>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">SKU Aman:</span>
                <span class="font-bold text-slate-800">{{ $normalCount }} / {{ $totalSKU }} SKU</span>
            </div>
        </div>

    </div>

    <!-- 3. Working Capital & Inventory Risk Analysis (2 Cards) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        
        <!-- Card 1: Alokasi Modal Reorder Dibutuhkan (Red Zone Risk) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></div>
                        <h3 class="text-xs font-bold text-navy-900 uppercase tracking-wider">Kebutuhan Alokasi Modal Reorder (Draft PO)</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">{{ $kritisCount }} SKU Kritis</span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div>
                        <div class="text-2xl font-black text-rose-600 font-mono">
                            Rp {{ number_format($totalReorderBudget, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Total estimasi modal kerja yang perlu dialokasikan agar stok tidak mengalami kekosongan (*stockout*).
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Rekomendasi Manajerial:</span>
                <a href="{{ route('rbl.analisis') }}" class="font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                    <span>Tinjau Rekomendasi RBL &rarr;</span>
                </a>
            </div>
        </div>

        <!-- Card 2: Modal Tertahan pada Overstock (Blue Zone Risk) -->
        @php
            $overstockCapital = $berlebihProducts->sum(function($p) {
                $excessQty = max(0, $p->stok_aktual - $p->batas_maksimum);
                return $excessQty * ($p->harga_beli_per_satuan ?? 0);
            });
        @endphp
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <h3 class="text-xs font-bold text-navy-900 uppercase tracking-wider">Modal Tertahan pada Overstock (Working Capital Locked)</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">{{ $berlebihCount }} SKU Berlebih</span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div>
                        <div class="text-2xl font-black text-blue-700 font-mono">
                            Rp {{ number_format($overstockCapital, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Dana tertahan akibat stok melebihi kapasitas maksimum buffer. Perlu diprioritaskan untuk distribusi/penjualan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Tindakan SOP:</span>
                <span class="font-bold text-slate-800">Tahan PO Baru, Prioritaskan Penjualan</span>
            </div>
        </div>

    </div>

    <!-- 4. Executive Dual Charts (Line Trend & Category Donut) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Interactive Multi-Period Trend Chart (8 Cols) -->
        <div class="lg:col-span-8 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div>
                    <h3 class="text-sm font-bold text-navy-900">Tren Arus Barang Masuk (+IN) vs Pengeluaran (-OUT)</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Analisis dinamika pasokan vendor konveksi dan laju distribusi outlet/marketplace.</p>
                </div>

                <!-- Interactive Multi-Period Switcher -->
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold" x-data="{ activeRange: 'semester' }">
                    <button type="button" @click="activeRange = 'harian'; switchTrendRange('harian')" :class="activeRange === 'harian' ? 'bg-white text-navy-900 shadow-xs' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition-all">Harian</button>
                    <button type="button" @click="activeRange = 'mingguan'; switchTrendRange('mingguan')" :class="activeRange === 'mingguan' ? 'bg-white text-navy-900 shadow-xs' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition-all">Mingguan</button>
                    <button type="button" @click="activeRange = 'semester'; switchTrendRange('semester')" :class="activeRange === 'semester' ? 'bg-white text-navy-900 shadow-xs' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition-all">6 Bulan</button>
                    <button type="button" @click="activeRange = 'bulanan'; switchTrendRange('bulanan')" :class="activeRange === 'bulanan' ? 'bg-white text-navy-900 shadow-xs' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition-all">12 Bulan</button>
                </div>
            </div>

            <div class="relative h-72 w-full mt-4">
                <canvas id="mutationTrendChart"></canvas>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Penerimaan (+IN)</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Pengeluaran (-OUT)</span>
                </div>
                <span class="font-mono text-[11px] text-slate-400">Data Terkini</span>
            </div>
        </div>

        <!-- Right: Category Donut Breakdown (4 Cols) -->
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-navy-900">Distribusi Valuasi per Kategori</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Proporsi modal aset pakaian jadi.</p>
                </div>

                <div class="relative h-48 w-full my-3 flex items-center justify-center">
                    <canvas id="categoryDonutChart"></canvas>
                </div>

                <div class="space-y-2 text-xs divide-y divide-slate-50 max-h-48 overflow-y-auto custom-scrollbar pr-1">
                    @foreach($categoryBreakdown as $cat)
                        <div class="flex items-center justify-between pt-1.5">
                            <span class="text-slate-600 truncate max-w-[130px]">{{ $cat['name'] }}</span>
                            <div class="text-right">
                                <span class="font-mono font-bold text-slate-900">Rp {{ number_format($cat['valuation'], 0, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-400 block">({{ $cat['percentage'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">
                    Buka Katalog Produk Pakaian Jadi &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- 5. Top 5 SKU with Highest Valuation & Supplier Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Table: Top 5 SKU Berdasarkan Valuasi Aset Terbesar -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-navy-900 uppercase tracking-wider">Top 5 SKU Valuasi Aset Terbesar</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Produk apparel dengan alokasi modal persediaan tertinggi.</p>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">Aset Kunci</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-semibold">
                        <tr>
                            <th class="py-2.5 px-4">SKU / Produk</th>
                            <th class="py-2.5 px-3 text-right">Stok</th>
                            <th class="py-2.5 px-3 text-right">Harga Beli</th>
                            <th class="py-2.5 px-4 text-right">Total Valuasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @php
                            $topValuationProducts = $allProducts->sortByDesc(function($p) {
                                return $p->stok_aktual * ($p->harga_beli_per_satuan ?? 0);
                            })->take(5);
                        @endphp
                        @foreach($topValuationProducts as $item)
                            <tr class="hover:bg-slate-50/75 transition-colors">
                                <td class="py-3 px-4">
                                    <span class="text-[10px] font-mono text-slate-400 font-semibold">[{{ $item->kode_produk }}]</span>
                                    <div class="font-bold text-slate-900 truncate max-w-[180px]">{{ $item->nama }}</div>
                                </td>
                                <td class="py-3 px-3 text-right font-bold text-slate-800 whitespace-nowrap">
                                    {{ number_format($item->stok_aktual) }} {{ $item->satuan }}
                                </td>
                                <td class="py-3 px-3 text-right text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($item->harga_beli_per_satuan ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-navy-900 whitespace-nowrap">
                                    Rp {{ number_format($item->stok_aktual * ($item->harga_beli_per_satuan ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table: Evaluasi Pemasok & Vendor Konveksi Utama -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-navy-900 uppercase tracking-wider">Evaluasi Kinerja Vendor & Pemasok</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Mitra konveksi garmen, lead time pengiriman, dan jumlah SKU yang disuplai.</p>
                </div>
                <a href="{{ route('purchasing.nota.index') }}" class="text-[11px] font-bold text-orange-600 hover:underline">Lihat Nota &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-semibold">
                        <tr>
                            <th class="py-2.5 px-4">Nama Vendor Konveksi</th>
                            <th class="py-2.5 px-3 text-center">Varian SKU</th>
                            <th class="py-2.5 px-3 text-center">Rata-rata Lead Time</th>
                            <th class="py-2.5 px-4 text-right">Total Stok Suplai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($topSuppliers->take(5) as $sup)
                            <tr class="hover:bg-slate-50/75 transition-colors">
                                <td class="py-3 px-4 font-bold text-navy-900">
                                    {{ $sup['supplier'] }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                                        {{ $sup['sku_count'] }} SKU
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center font-medium text-slate-600">
                                    {{ $sup['avg_lead_time'] }} Hari
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-slate-900">
                                    {{ number_format($sup['total_stock']) }} Pcs
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
