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

    <!-- 2. Main Purchasing Section (Rekomendasi Reorder & Direktori Supplier) -->
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

    </div>

</div>
