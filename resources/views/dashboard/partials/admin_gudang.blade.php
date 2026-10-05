<!-- Admin Gudang Workspace (Fokus Operasional Lapangan, Inbound Barang Jadi, Outbound Distribusi, & Rak) -->
<div class="space-y-6">
    
    <!-- 1. Top Admin Gudang KPI Tiles -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- KPI 1: Penerimaan Masuk Hari Ini -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Penerimaan Masuk Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-emerald-600 tracking-tight">
                    +{{ number_format($todayInboundQty) }} <span class="text-xs font-semibold text-slate-500">Pcs</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <strong class="text-emerald-700">{{ $todayInboundCount }}</strong> Surat Jalan dari Vendor Hari Ini
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Pencatatan Masuk:</span>
                <a href="{{ route('transaksi.masuk') }}" class="font-bold text-emerald-600 hover:underline">+ Form Masuk &rarr;</a>
            </div>
        </div>

        <!-- KPI 2: Pengeluaran Keluar Hari Ini -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Pengeluaran Keluar Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-orange-600 tracking-tight">
                    -{{ number_format($todayOutboundQty) }} <span class="text-xs font-semibold text-slate-500">Pcs</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <strong class="text-orange-700">{{ $todayOutboundCount }}</strong> Pengiriman Pesanan / Outlet
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Pencatatan Keluar:</span>
                <a href="{{ route('transaksi.keluar') }}" class="font-bold text-orange-600 hover:underline">- Form Keluar &rarr;</a>
            </div>
        </div>

        <!-- KPI 3: Total Fisik Tersimpan Saat Ini -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Fisik Tersimpan di Rak Gudang</span>
                <div class="w-8 h-8 rounded-xl bg-navy-50 text-navy-800 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-navy-900 tracking-tight">
                    {{ number_format($totalStokFisik) }} <span class="text-xs font-semibold text-slate-500">Pcs</span>
                </div>
                <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500">
                    <span><strong>{{ $totalSKU }}</strong> SKU Pakaian Jadi</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Kapasitas Maks:</span>
                <span class="font-bold text-slate-700">{{ number_format($totalKapasitasMaks) }} Pcs</span>
            </div>
        </div>

        <!-- KPI 4: Quick Scan & Verifikasi SKU -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Scanner Label Barcode / QR</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <button 
                    type="button" 
                    @click="window.triggerBarcodeScan({ title: 'Scan Barcode Cepat (SKU Produk)', callback: (code) => { const el = document.getElementById('topbar_search_input'); if(el){ el.value = code; document.getElementById('topbar_search_form').submit(); } } })"
                    class="w-full py-2 px-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs transition-colors flex items-center justify-center gap-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Buka Scanner Kamera</span>
                </button>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Cek Cepat:</span>
                <span class="font-medium text-slate-700">Scan hangtag / label pakaian</span>
            </div>
        </div>

    </div>

    <!-- 2. Large Action Stations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <!-- Action Station 1: Penerimaan Barang Masuk -->
        <a href="{{ route('transaksi.masuk') }}" class="p-6 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white shadow-md hover:shadow-lg transition-all group relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white uppercase tracking-wider">
                        MODUL INBOUND
                    </span>
                    <h3 class="text-lg font-bold text-white mt-2">Penerimaan Pakaian Jadi Masuk</h3>
                    <p class="text-xs text-emerald-100 mt-1 max-w-sm">
                        Catat barang jadi (hoodie, kaos, jaket, dll) yang tiba dari vendor konveksi dengan nomor Surat Jalan dan Batch QC.
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </div>
            </div>
            <div class="mt-5 flex items-center gap-2 text-xs font-bold text-white group-hover:translate-x-1 transition-transform">
                <span>Buka Form Stok Masuk</span>
                <span>&rarr;</span>
            </div>
        </a>

        <!-- Action Station 2: Pengeluaran Barang Keluar -->
        <a href="{{ route('transaksi.keluar') }}" class="p-6 rounded-2xl bg-gradient-to-br from-orange-500 to-candescent-700 text-white shadow-md hover:shadow-lg transition-all group relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white uppercase tracking-wider">
                        MODUL OUTBOUND
                    </span>
                    <h3 class="text-lg font-bold text-white mt-2">Pengeluaran Distribusi & Pesanan</h3>
                    <p class="text-xs text-orange-100 mt-1 max-w-sm">
                        Keluarkan stok pakaian jadi untuk pengiriman ke toko cabang, marketplace order, atau pesanan buyer dengan validasi sisa stok.
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                </div>
            </div>
            <div class="mt-5 flex items-center gap-2 text-xs font-bold text-white group-hover:translate-x-1 transition-transform">
                <span>Buka Form Stok Keluar</span>
                <span>&rarr;</span>
            </div>
        </a>

    </div>

    <!-- 3. Real-Time Mutation Activity Log (Dual Column Inbound & Outbound) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Left: Log Penerimaan Masuk Terkini (+IN) -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-navy-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Riwayat Penerimaan Masuk Terkini (+IN)</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Penerimaan dari vendor konveksi / pabrik</p>
                </div>
                <a href="{{ route('transaksi.masuk') }}" class="text-xs font-semibold text-emerald-600 hover:underline">Semua &rarr;</a>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($recentInbound as $in)
                    <div class="py-3 flex items-start justify-between gap-3 hover:bg-slate-50/70 px-1 rounded-lg transition-colors">
                        <div class="min-w-0">
                            <div class="font-bold text-navy-900 truncate">
                                [{{ $in->product->kode_produk ?? 'SKU' }}] {{ $in->product->nama ?? 'Produk Apparel' }}
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5 flex flex-wrap items-center gap-2">
                                <span>No. SJ: <strong>{{ $in->no_surat_jalan ?: '-' }}</strong></span>
                                <span>&bull;</span>
                                <span>Batch: <strong>{{ $in->no_batch_lot ?: '-' }}</strong></span>
                                <span>&bull;</span>
                                <span>Vendor: <strong>{{ $in->supplier ?: '-' }}</strong></span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="font-black text-emerald-600 text-sm block">+{{ $in->jumlah }} {{ $in->product->satuan ?? 'Pcs' }}</span>
                            <span class="text-[10px] text-slate-400">{{ $in->tanggal }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">Belum ada transaksi penerimaan barang.</div>
                @endforelse
            </div>
        </div>

        <!-- Right: Log Pengeluaran Keluar Terkini (-OUT) -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-navy-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span>Riwayat Pengeluaran Keluar Terkini (-OUT)</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pengiriman pesanan outlet & marketplace</p>
                </div>
                <a href="{{ route('transaksi.keluar') }}" class="text-xs font-semibold text-orange-600 hover:underline">Semua &rarr;</a>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($recentOutbound as $out)
                    <div class="py-3 flex items-start justify-between gap-3 hover:bg-slate-50/70 px-1 rounded-lg transition-colors">
                        <div class="min-w-0">
                            <div class="font-bold text-navy-900 truncate">
                                [{{ $out->product->kode_produk ?? 'SKU' }}] {{ $out->product->nama ?? 'Produk Apparel' }}
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5 flex flex-wrap items-center gap-2">
                                <span>No. DO/SPK: <strong>{{ $out->no_spk_tujuan ?: '-' }}</strong></span>
                                <span>&bull;</span>
                                <span>Tujuan: <strong>{{ $out->penerima_divisi ?: '-' }}</strong></span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="font-black text-orange-600 text-sm block">-{{ $out->jumlah }} {{ $out->product->satuan ?? 'Pcs' }}</span>
                            <span class="text-[10px] text-slate-400">{{ $out->tanggal }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">Belum ada transaksi pengeluaran barang.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- 4. Peta Cepat Penempatan Rak Gudang -->
    <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-navy-900">Peta Lokasi Rak Gudang (Panduan Penempatan Pakaian Jadi)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Memudahkan staf meletakkan dan mengambil stok pakaian berdasarkan rak</p>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                {{ $warehouseRacks->count() }} Lokasi Rak
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
            @foreach($warehouseRacks as $rack)
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-navy-900 text-xs">{{ $rack['rack'] }}</span>
                        @if($rack['has_kritis'])
                            <span class="text-[9px] font-bold px-1 rounded bg-rose-100 text-rose-700">Kritis</span>
                        @else
                            <span class="text-[9px] font-bold px-1 rounded bg-emerald-100 text-emerald-700">Aman</span>
                        @endif
                    </div>
                    <div class="mt-2 flex items-baseline justify-between text-xs">
                        <span class="text-[11px] text-slate-500">{{ $rack['count'] }} Jenis SKU</span>
                        <span class="font-black text-navy-900">{{ number_format($rack['total_stock']) }} Pcs</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
