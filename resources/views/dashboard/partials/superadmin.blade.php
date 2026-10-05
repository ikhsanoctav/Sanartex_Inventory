<!-- Superadmin Executive Workspace (Total Kontrol, Valuasi Finansial Pakaian Jadi, Dual Charts, & Matriks RBL) -->
<div class="space-y-6">
    
    <!-- 1. Top Executive Metric Cards (4 KPI Tiles) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- KPI 1: Total Valuasi Persediaan Barang Jadi -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Valuasi Persediaan Pakaian Jadi</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-navy-900 tracking-tight">
                    Rp {{ number_format($totalValuasi, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-2 mt-1.5 text-[11px] text-slate-500">
                    <span class="font-medium"><strong>{{ number_format($totalStokFisik) }}</strong> Pcs Barang Jadi</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Total Varian SKU:</span>
                <span class="font-bold text-navy-900">{{ $totalSKU }} Produk Apparel</span>
            </div>
        </div>

        <!-- KPI 2: Skor Kesehatan Buffer RBL -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Kesehatan Buffer RBL</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="flex items-baseline gap-2">
                    <div class="text-xl sm:text-2xl font-black text-emerald-600 tracking-tight">
                        {{ $healthScore }}%
                    </div>
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">OPTIMAL</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden flex">
                    <div class="bg-emerald-500 h-2" style="width: {{ $healthScore }}%"></div>
                    <div class="bg-rose-500 h-2" style="width: {{ $totalSKU > 0 ? ($kritisCount / $totalSKU)*100 : 0 }}%"></div>
                    <div class="bg-blue-500 h-2" style="width: {{ $totalSKU > 0 ? ($berlebihCount / $totalSKU)*100 : 0 }}%"></div>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">SKU Sesuai Aturan:</span>
                <span class="font-bold text-slate-800">{{ $normalCount }} / {{ $totalSKU }} Produk</span>
            </div>
        </div>

        <!-- KPI 3: Mutasi Masuk Bulan Ini -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Penerimaan Masuk (Bulan Ini)</span>
                <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-teal-700 tracking-tight">
                    +{{ number_format($inboundThisMonthQty) }} <span class="text-xs font-semibold text-slate-500">Pcs</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span class="font-bold text-teal-600">{{ $inboundThisMonthCount }}</span> Surat Jalan dari Pabrik
                    @if($inboundGrowth != 0)
                        <span class="text-[10px] font-bold px-1 rounded {{ $inboundGrowth > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                            {{ $inboundGrowth > 0 ? '+' : '' }}{{ $inboundGrowth }}%
                        </span>
                    @endif
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Modul Inbound:</span>
                <a href="{{ route('transaksi.masuk') }}" class="font-bold text-teal-700 hover:underline">Kelola &rarr;</a>
            </div>
        </div>

        <!-- KPI 4: Total Pengguna Sistem -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Pengguna & Hak Akses</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-purple-700 tracking-tight">
                    {{ $totalUsers }} <span class="text-xs font-semibold text-slate-500">User</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span>4 Role: Superadmin, Kepala, Admin, Purchasing</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Manajemen Akun:</span>
                <a href="{{ route('users.index') }}" class="font-bold text-purple-700 hover:underline">Kelola Staf &rarr;</a>
            </div>
        </div>

    </div>

    <!-- 2. Interactive 3-Tier RBL Health Status Cards (Drill-Down Supported) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ 
        openModal: null, 
        modalSearch: '',
        kritisList: {{ Js::from($kritisProducts) }},
        normalList: {{ Js::from($normalProducts) }},
        berlebihList: {{ Js::from($berlebihProducts) }},
        get activeModalProducts() {
            let list = [];
            if (this.openModal === 'kritis') list = this.kritisList;
            else if (this.openModal === 'normal') list = this.normalList;
            else if (this.openModal === 'berlebih') list = this.berlebihList;
            
            if (!this.modalSearch.trim()) return list;
            const q = this.modalSearch.toLowerCase();
            return list.filter(p => 
                (p.nama && p.nama.toLowerCase().includes(q)) ||
                (p.kode_produk && p.kode_produk.toLowerCase().includes(q)) ||
                (p.kategori && p.kategori.toLowerCase().includes(q)) ||
                (p.lokasi_rak && p.lokasi_rak.toLowerCase().includes(q))
            );
        }
    }">
        <!-- 🔴 Zona Kritis -->
        <div @click="openModal = 'kritis'; modalSearch = ''" 
             class="cursor-pointer p-4 rounded-2xl bg-gradient-to-br from-white to-rose-50/40 border border-rose-200 hover:border-rose-400 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></span>
                    <span class="text-xs font-bold text-rose-700">🔴 Zona Kritis (Stok Habis / Menipis)</span>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-200">
                    &le; Batas Min
                </span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-black text-rose-600 tracking-tight">{{ $kritisCount }}</span>
                <span class="text-xs font-semibold text-rose-700 bg-rose-100/80 px-2 py-0.5 rounded-lg">
                    Urgent Reorder
                </span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2 leading-relaxed">
                Stok pakaian jadi di bawah batas minimum. Perlu restock / order produksi baru segera ke vendor konveksi.
            </p>
            <div class="mt-3 pt-2 border-t border-rose-100 flex items-center justify-between text-[11px] font-semibold text-rose-600">
                <span>Klik untuk drill-down produk</span>
                <span>&rarr;</span>
            </div>
        </div>

        <!-- 🟢 Zona Normal -->
        <div @click="openModal = 'normal'; modalSearch = ''" 
             class="cursor-pointer p-4 rounded-2xl bg-gradient-to-br from-white to-emerald-50/40 border border-emerald-200 hover:border-emerald-400 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-emerald-700">🟢 Zona Normal (Stok Aman)</span>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                    Min &ndash; Max
                </span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-black text-emerald-600 tracking-tight">{{ $normalCount }}</span>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-lg">
                    Stok Optimal
                </span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2 leading-relaxed">
                Persediaan pakaian jadi berada dalam ambang batas ideal untuk kelancaran pengiriman pesanan toko & marketplace.
            </p>
            <div class="mt-3 pt-2 border-t border-emerald-100 flex items-center justify-between text-[11px] font-semibold text-emerald-600">
                <span>Klik untuk drill-down produk</span>
                <span>&rarr;</span>
            </div>
        </div>

        <!-- 🔵 Zona Berlebih -->
        <div @click="openModal = 'berlebih'; modalSearch = ''" 
             class="cursor-pointer p-4 rounded-2xl bg-gradient-to-br from-white to-blue-50/40 border border-blue-200 hover:border-blue-400 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <span class="text-xs font-bold text-blue-700">🔵 Zona Berlebih (Overstock)</span>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                    &gt; Batas Maks
                </span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-black text-blue-600 tracking-tight">{{ $berlebihCount }}</span>
                <span class="text-xs font-semibold text-blue-700 bg-blue-100/80 px-2 py-0.5 rounded-lg">
                    Overstock Alert
                </span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2 leading-relaxed">
                Stok pakaian jadi melebihi kapasitas simpan rak. Disarankan genjot promosi penjualan atau tunda PO baru.
            </p>
            <div class="mt-3 pt-2 border-t border-blue-100 flex items-center justify-between text-[11px] font-semibold text-blue-600">
                <span>Klik untuk drill-down produk</span>
                <span>&rarr;</span>
            </div>
        </div>

        <!-- Drill Down Modal -->
        <div x-show="openModal" 
             x-cloak
             @click.self="openModal = null"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-xs"
             x-transition>
            <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-2xl p-6 shadow-2xl flex flex-col max-h-[85vh]">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-navy-900 flex items-center gap-2">
                        <span>Daftar Produk Apparel:</span>
                        <span class="uppercase font-extrabold text-orange-600" x-text="'Zona ' + openModal"></span>
                        <span class="text-xs font-normal text-slate-400" x-text="'(' + activeModalProducts.length + ' item)'"></span>
                    </h3>
                    <button @click="openModal = null" class="text-slate-400 hover:text-slate-700 text-xl font-bold leading-none">&times;</button>
                </div>

                <div class="mt-3">
                    <input 
                        type="text" 
                        x-model="modalSearch" 
                        placeholder="Cari SKU, nama produk (hoodie, kaos, jaket...), atau rak..." 
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500">
                </div>

                <div class="mt-3 flex-1 overflow-y-auto custom-scrollbar border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="text-slate-500 bg-slate-50 uppercase text-[10px] font-semibold border-b border-slate-200 sticky top-0 z-10">
                            <tr>
                                <th class="p-2.5">SKU</th>
                                <th class="p-2.5">Nama Produk</th>
                                <th class="p-2.5">Stok Fisik</th>
                                <th class="p-2.5">Min / Max</th>
                                <th class="p-2.5">Lokasi Rak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <template x-for="p in activeModalProducts" :key="p.id">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-2.5 font-mono font-bold text-navy-900" x-text="p.kode_produk"></td>
                                    <td class="p-2.5">
                                        <div class="font-medium text-slate-900" x-text="p.nama"></div>
                                        <div class="text-[10px] text-slate-400" x-text="p.kategori"></div>
                                    </td>
                                    <td class="p-2.5 font-bold" 
                                        :class="openModal === 'kritis' ? 'text-rose-600' : (openModal === 'normal' ? 'text-emerald-600' : 'text-blue-600')"
                                        x-text="p.stok_aktual + ' ' + p.satuan">
                                    </td>
                                    <td class="p-2.5 text-slate-500" x-text="'Min: ' + p.batas_minimum + ' / Max: ' + p.batas_maksimum"></td>
                                    <td class="p-2.5 text-slate-500" x-text="p.lokasi_rak || '-'"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <button @click="openModal = null" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Dual Visual Analytics & Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Chart 1: Tren Mutasi Masuk vs Keluar dengan Filter Multi-Periode -->
        <div class="lg:col-span-2 p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between"
             x-data="{
                activeRange: 'semester',
                datasets: {{ Js::from($trendDatasets) }},
                get current() {
                    return this.datasets[this.activeRange] || this.datasets['semester'];
                },
                selectRange(range) {
                    this.activeRange = range;
                    if (window.switchTrendRange) {
                        window.switchTrendRange(range);
                    }
                }
             }">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-navy-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                            <span>Tren Arus Mutasi Pakaian Jadi</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Penerimaan dari vendor (<span class="text-emerald-600 font-semibold">+IN</span>) vs pengiriman pesanan (<span class="text-orange-600 font-semibold">-OUT</span>) &bull; <span class="font-bold text-slate-700" x-text="current.title"></span>
                        </p>
                    </div>

                    <!-- Range Selector Pills (Harian, Mingguan, Triwulan, Semester, Bulanan) -->
                    <div class="flex flex-wrap items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200/80">
                        <button 
                            type="button" 
                            @click="selectRange('harian')"
                            :class="activeRange === 'harian' ? 'bg-white text-navy-900 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                            class="px-2 py-1 text-[11px] rounded-lg transition-all cursor-pointer">
                            Harian
                        </button>
                        <button 
                            type="button" 
                            @click="selectRange('mingguan')"
                            :class="activeRange === 'mingguan' ? 'bg-white text-navy-900 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                            class="px-2 py-1 text-[11px] rounded-lg transition-all cursor-pointer">
                            Mingguan
                        </button>
                        <button 
                            type="button" 
                            @click="selectRange('triwulan')"
                            :class="activeRange === 'triwulan' ? 'bg-white text-navy-900 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                            class="px-2 py-1 text-[11px] rounded-lg transition-all cursor-pointer">
                            3 Bulan (Triwulan)
                        </button>
                        <button 
                            type="button" 
                            @click="selectRange('semester')"
                            :class="activeRange === 'semester' ? 'bg-white text-navy-900 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                            class="px-2 py-1 text-[11px] rounded-lg transition-all cursor-pointer">
                            6 Bulan (Semester)
                        </button>
                        <button 
                            type="button" 
                            @click="selectRange('bulanan')"
                            :class="activeRange === 'bulanan' ? 'bg-white text-navy-900 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                            class="px-2 py-1 text-[11px] rounded-lg transition-all cursor-pointer">
                            Bulanan
                        </button>
                    </div>
                </div>

                <div class="h-64 sm:h-72 w-full relative">
                    <canvas id="mutationTrendChart"></canvas>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-xs">
                <div class="p-2 rounded-xl bg-slate-50">
                    <span class="text-[10px] text-slate-400 block uppercase font-semibold" x-text="'Total Masuk (' + current.short_title + ')'">Total Masuk</span>
                    <span class="text-sm font-black text-emerald-600 mt-0.5 block" x-text="'+' + current.total_in.toLocaleString('id-ID') + ' Pcs'"></span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50">
                    <span class="text-[10px] text-slate-400 block uppercase font-semibold" x-text="'Total Keluar (' + current.short_title + ')'">Total Keluar</span>
                    <span class="text-sm font-black text-orange-600 mt-0.5 block" x-text="'-' + current.total_out.toLocaleString('id-ID') + ' Pcs'"></span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50">
                    <span class="text-[10px] text-slate-400 block uppercase font-semibold">Net Arus Stok</span>
                    <span class="text-sm font-black text-navy-900 mt-0.5 block" x-text="(current.net >= 0 ? '+' : '') + current.net.toLocaleString('id-ID') + ' Pcs'"></span>
                </div>
            </div>
        </div>

        <!-- Chart 2: Komposisi Kategori Pakaian Jadi (Donut Chart) -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-sm font-bold text-navy-900">Kategori Pakaian Jadi</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Proporsi valuasi stok per kategori produk</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                        {{ $categoryBreakdown->count() }} Kategori
                    </span>
                </div>

                <div class="h-48 relative flex items-center justify-center">
                    <canvas id="categoryDonutChart"></canvas>
                </div>

                <div class="space-y-2 mt-3 max-h-36 overflow-y-auto custom-scrollbar pr-1">
                    @foreach($categoryBreakdown as $cat)
                        <div class="text-xs">
                            <div class="flex justify-between items-center text-[11px] mb-1">
                                <span class="font-semibold text-slate-800">{{ $cat['name'] }} ({{ $cat['sku_count'] }} SKU)</span>
                                <span class="font-bold text-navy-900">{{ $cat['percentage'] }}% &bull; Rp {{ number_format($cat['valuation']/1000000, 1) }}M</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-navy-800 h-1.5 rounded-full" style="width: {{ $cat['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                <a href="{{ route('products.index') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700">
                    Lihat Katalog Produk Lengkap &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- 4. Operational Activity Stream & Fast Moving Fabrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Left: Rekomendasi Pengadaan Cepat -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-navy-900">Rekomendasi Restock / Reorder Produk</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Item kritis yang perlu segera dibuatkan PO ke vendor</p>
                </div>
                <a href="{{ route('rbl.analisis') }}" class="text-xs font-semibold text-orange-600 hover:underline">Analisis Detail &rarr;</a>
            </div>

            @if($reorderSuggestions->count() > 0)
                <div class="space-y-2 max-h-72 overflow-y-auto custom-scrollbar pr-1">
                    @foreach($reorderSuggestions as $rec)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                            <div>
                                <div class="font-bold text-navy-900">[{{ $rec['product']->kode_produk }}] {{ $rec['product']->nama }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    Sisa: <strong class="text-rose-600">{{ $rec['product']->stok_aktual }} {{ $rec['product']->satuan }}</strong> &bull;
                                    Saran Restock: <strong>+{{ $rec['suggested_qty'] }} {{ $rec['product']->satuan }}</strong> &bull;
                                    Est: Rp {{ number_format($rec['estimated_cost'], 0, ',', '.') }}
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">
                                {{ $rec['product']->status_stok }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Seluruh stok pakaian jadi dalam batas aman buffer RBL.</span>
                </div>
            @endif
        </div>

        <!-- Right: Top 5 Fast Moving Apparel -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-navy-900">Top 5 Produk Apparel Paling Laris (*Fast-Moving*)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Produk dengan perputaran dan pengiriman tertinggi</p>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-200">
                    High Demand
                </span>
            </div>

            <div class="space-y-2.5">
                @foreach($fastMovingProducts as $index => $item)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-6 h-6 rounded-lg bg-navy-900 text-white font-black text-xs flex items-center justify-center flex-shrink-0">
                                #{{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <div class="font-bold text-navy-900 truncate">[{{ $item['product']->kode_produk }}] {{ $item['product']->nama }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">
                                    Kategori: {{ $item['product']->kategori }} &bull; Rak: {{ $item['product']->lokasi_rak ?? 'Area Utama' }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="font-black text-orange-600 text-xs">{{ number_format($item['out_qty']) }} {{ $item['product']->satuan }}</div>
                            <span class="text-[10px] text-slate-400">Total Terkirim</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
