@php
    $user = Auth::user();
    $currentRoute = Route::currentRouteName();

    // Hitung status stok real-time untuk badge sidebar
    $kritisCount = \App\Models\Product::where('status_stok', 'KRITIS')->count();

    // Role checks
    $isSuperadmin = $user && $user->isSuperadmin();
    $isManajemen = $user && ($user->isManajemen());
    $isKepalaGudang = $user && ($user->role === 'kepala_gudang' || $isManajemen || $isSuperadmin);
    $isAdminGudang = $user && ($user->role === 'admin_gudang' || $isSuperadmin);
    $isPurchasing = $user && ($user->role === 'purchasing' || $isManajemen || $isSuperadmin);
@endphp

<aside 
    x-data="{
        tooltip: { visible: false, top: 0, title: '', badge: '', badgeClass: '', shortcut: '' },
        showTip(el, title, badge = '', badgeClass = '', shortcut = '') {
            if (!this.sidebarCollapsed) return;
            const rect = el.getBoundingClientRect();
            this.tooltip = {
                visible: true,
                top: rect.top + (rect.height / 2) - 18,
                title: title,
                badge: badge,
                badgeClass: badgeClass,
                shortcut: shortcut
            };
        },
        hideTip() {
            this.tooltip.visible = false;
        }
    }"
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen,
        'lg:w-20': sidebarCollapsed,
        'lg:w-64': !sidebarCollapsed
    }"
    class="fixed inset-y-0 left-0 z-40 w-64 flex-shrink-0 bg-[#0a1526] border-r border-slate-800/90 transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] lg:translate-x-0 lg:static lg:inset-auto flex flex-col justify-between text-slate-300 relative group/aside select-none">
    
    <!-- Floating Edge Collapse Button (Modern Floating Rail Pill) -->
    <button 
        type="button" 
        @click="toggleCollapse()" 
        class="hidden lg:flex absolute -right-3.5 top-7 w-7 h-7 rounded-full bg-[#0d1c31] border border-slate-700/80 text-slate-400 hover:text-orange-400 hover:border-orange-500 shadow-md shadow-black/40 items-center justify-center transition-all duration-200 z-50 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-orange-500/30"
        :title="sidebarCollapsed ? 'Perluas Sidebar (Ctrl+B)' : 'Ciutkan Sidebar (Ctrl+B)'">
        <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180 text-orange-400' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <div class="flex flex-col flex-1 min-h-0">
        <!-- Logo Header -->
        <div class="h-20 flex items-center border-b border-slate-800/90 bg-[#081120] transition-all duration-300"
             :class="sidebarCollapsed ? 'px-3 justify-center' : 'px-5 justify-between'">
            <a href="{{ route('dashboard') }}" class="flex items-center group/logo focus:outline-none">
                <!-- Expanded Full Logo -->
                <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="bg-white/95 px-3 py-1.5 rounded-xl shadow-xs inline-flex items-center hover:bg-white transition-all group-hover/logo:shadow-md">
                    <img src="{{ asset('img/sanartex_horizontal.png') }}?v=4" alt="Logo Sanartex" class="h-8 sm:h-9 w-auto max-w-[170px] object-contain">
                </div>
                <!-- Collapsed Emblem -->
                <div x-show="sidebarCollapsed" x-transition.opacity.duration.200ms 
                     @mouseenter="showTip($el, 'PT SANARTEX', 'Sistem RBL', 'bg-orange-500/20 text-orange-300 border border-orange-500/30')"
                     @mouseleave="hideTip()"
                     class="bg-white/95 p-1.5 rounded-xl shadow-xs inline-flex items-center justify-center w-11 h-11 hover:bg-white transition-all hover:scale-105 group-hover/logo:shadow-md">
                    <img src="{{ asset('img/favicon.png') }}" alt="Logo Sanartex" class="w-7 h-7 object-contain">
                </div>
            </a>
            
            <!-- Mobile Close Button -->
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- User Quick Info -->
        <div class="border-b border-slate-800/80 bg-slate-900/40 flex items-center transition-all duration-300"
             :class="sidebarCollapsed ? 'p-3 justify-center' : 'px-5 py-3.5 gap-3'">
            <a href="{{ route('profile.index') }}" 
               @mouseenter="showTip($el, '{{ $user->name ?? 'Pengguna' }}', '{{ $user->role_label ?? 'Staff' }}', 'bg-orange-500/20 text-orange-300 border border-orange-500/30')"
               @mouseleave="hideTip()"
               class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-2 ring-orange-500/20 hover:ring-orange-400/50 transition-all hover:scale-105 focus:outline-none">
                {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
            </a>
            <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="min-w-0 flex-1 overflow-hidden">
                <a href="{{ route('profile.index') }}" class="block group/uname">
                    <p class="text-xs font-semibold text-white truncate group-hover/uname:text-orange-400 transition-colors">{{ $user->name ?? 'Pengguna' }}</p>
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400 truncate mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="truncate">{{ $user->role_label ?? 'Staff' }}</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-3 space-y-1.5 flex-1 overflow-y-auto custom-scrollbar text-xs font-medium">
            
            <!-- Category: Menu Utama -->
            <div x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="px-3 pt-2.5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400/80">
                Menu Utama
            </div>
            <div x-show="sidebarCollapsed" class="w-8 h-px bg-slate-800/80 mx-auto my-2"></div>

            <!-- Dashboard (All Roles) -->
            <a href="{{ route('dashboard') }}" 
               @mouseenter="showTip($el, 'Dashboard', 'Utama', 'bg-blue-500/20 text-blue-300 border border-blue-500/30')"
               @mouseleave="hideTip()"
               class="flex items-center rounded-xl transition-all relative group {{ $currentRoute === 'dashboard' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ $currentRoute === 'dashboard' ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ $currentRoute === 'dashboard' ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $currentRoute === 'dashboard' ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Dashboard</span>
            </a>

            <!-- Master Produk Tekstil (All Roles) -->
            <a href="{{ route('products.index') }}" 
               @mouseenter="showTip($el, 'Katalog Pakaian Jadi', 'Master SKU', 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30')"
               @mouseleave="hideTip()"
               class="flex items-center rounded-xl transition-all relative group {{ str_starts_with($currentRoute, 'products') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ str_starts_with($currentRoute, 'products') ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ str_starts_with($currentRoute, 'products') ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ str_starts_with($currentRoute, 'products') ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Katalog Pakaian Jadi</span>
            </a>

            <!-- Category: Mutasi Persediaan / Operasional -->
            <div x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400/80">
                {{ $user->role === 'purchasing' ? 'Pengadaan & Pembelian' : 'Mutasi Persediaan' }}
            </div>
            <div x-show="sidebarCollapsed" class="w-8 h-px bg-slate-800/80 mx-auto my-2"></div>

            <!-- Stok Masuk (Inbound Fisik: Gudang & Superadmin) -->
            @if($isSuperadmin || $user->role === 'kepala_gudang' || $user->role === 'admin_gudang')
                <a href="{{ route('transaksi.masuk') }}" 
                   @mouseenter="showTip($el, 'Stok Masuk (Inbound)', 'Barang Masuk', 'bg-sky-500/20 text-sky-300 border border-sky-500/30')"
                   @mouseleave="hideTip()"
                   class="flex items-center rounded-xl transition-all relative group {{ $currentRoute === 'transaksi.masuk' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ $currentRoute === 'transaksi.masuk' ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ $currentRoute === 'transaksi.masuk' ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $currentRoute === 'transaksi.masuk' ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Stok Masuk (Inbound)</span>
                </a>
            @endif

            <!-- Nota Pembelian & PO (Khusus Purchasing & Superadmin) -->
            @if($isPurchasing)
                <a href="{{ route('purchasing.nota.index') }}" 
                   @mouseenter="showTip($el, 'Nota Pembelian & PO', 'Faktur Pengadaan', 'bg-orange-500/20 text-orange-300 border border-orange-500/30')"
                   @mouseleave="hideTip()"
                   class="flex items-center rounded-xl transition-all relative group {{ str_starts_with($currentRoute, 'purchasing.nota') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ str_starts_with($currentRoute, 'purchasing.nota') ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ str_starts_with($currentRoute, 'purchasing.nota') ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ str_starts_with($currentRoute, 'purchasing.nota') ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Nota Pembelian & PO</span>
                </a>
            @endif

            <!-- Stok Keluar (Khusus Gudang & Superadmin - Bukan Purchasing) -->
            @if($isSuperadmin || $user->role === 'kepala_gudang' || $user->role === 'admin_gudang')
                <a href="{{ route('transaksi.keluar') }}" 
                   @mouseenter="showTip($el, 'Stok Keluar (Outbound)', 'Barang Keluar', 'bg-amber-500/20 text-amber-300 border border-amber-500/30')"
                   @mouseleave="hideTip()"
                   class="flex items-center rounded-xl transition-all relative group {{ $currentRoute === 'transaksi.keluar' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ $currentRoute === 'transaksi.keluar' ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ $currentRoute === 'transaksi.keluar' ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $currentRoute === 'transaksi.keluar' ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12" /></svg>
                    <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Stok Keluar (Outbound)</span>
                </a>
            @endif

            <!-- Category: Kontrol & Laporan (Hanya untuk Superadmin, Kepala Gudang, & Purchasing) -->
            @if($isSuperadmin || $user->role === 'kepala_gudang' || $user->role === 'purchasing')
                <div x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400/80">
                    Kontrol & Laporan
                </div>
                <div x-show="sidebarCollapsed" class="w-8 h-px bg-slate-800/80 mx-auto my-2"></div>

                <!-- Analisis RBL Buffer -->
                <a href="{{ route('rbl.analisis') }}" 
                   @mouseenter="showTip($el, 'Analisis RBL Buffer', $kritisCount > 0 ? '{{ $kritisCount }} Kritis' : 'Aman', $kritisCount > 0 ? 'bg-rose-500 text-white font-bold' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30')"
                   @mouseleave="hideTip()"
                   class="flex items-center rounded-xl transition-all relative group {{ $currentRoute === 'rbl.analisis' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ $currentRoute === 'rbl.analisis' ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'justify-between px-3.5 py-2.5 border-l-4 ' + ({{ $currentRoute === 'rbl.analisis' ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $currentRoute === 'rbl.analisis' ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Analisis RBL</span>
                    </div>
                    
                    <!-- Expanded Critical Badge Counter -->
                    @if($kritisCount > 0)
                        <span x-show="!sidebarCollapsed" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40 flex-shrink-0">
                            {{ $kritisCount }}
                        </span>
                        <!-- Collapsed Pulse Indicator Dot -->
                        <span x-show="sidebarCollapsed" class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-rose-500 ring-2 ring-[#0a1526] animate-pulse"></span>
                    @endif
                </a>

                <!-- Laporan & Mutasi -->
                <a href="{{ route('laporan.index') }}" 
                   @mouseenter="showTip($el, 'Laporan & Mutasi', 'Export Rekap', 'bg-slate-700 text-slate-200 border border-slate-600')"
                   @mouseleave="hideTip()"
                   class="flex items-center rounded-xl transition-all relative group {{ $currentRoute === 'laporan.index' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ $currentRoute === 'laporan.index' ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ $currentRoute === 'laporan.index' ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $currentRoute === 'laporan.index' ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Laporan & Mutasi</span>
                </a>
            @endif

            <!-- Category: Administrator (Superadmin Only) -->
            @if($isSuperadmin)
                <div x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400/80">
                    Administrator
                </div>
                <div x-show="sidebarCollapsed" class="w-8 h-px bg-slate-800/80 mx-auto my-2"></div>

                <a href="{{ route('users.index') }}" 
                   @mouseenter="showTip($el, 'Manajemen Pengguna', 'Superadmin', 'bg-purple-500/20 text-purple-300 border border-purple-500/30')"
                   @mouseleave="hideTip()"
                   class="flex items-center rounded-xl transition-all relative group {{ str_starts_with($currentRoute, 'users') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ str_starts_with($currentRoute, 'users') ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ str_starts_with($currentRoute, 'users') ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ str_starts_with($currentRoute, 'users') ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Manajemen Pengguna</span>
                </a>
            @endif

            <!-- Category: Akun & Bantuan (All Roles) -->
            <div x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400/80">
                Akun & Bantuan
            </div>
            <div x-show="sidebarCollapsed" class="w-8 h-px bg-slate-800/80 mx-auto my-2"></div>

            <!-- Profile Settings -->
            <a href="{{ route('profile.index') }}" 
               @mouseenter="showTip($el, 'Pengaturan Profil', 'Akun', 'bg-slate-700 text-slate-200 border border-slate-600')"
               @mouseleave="hideTip()"
               class="flex items-center rounded-xl transition-all relative group {{ str_starts_with($currentRoute, 'profile') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ str_starts_with($currentRoute, 'profile') ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ str_starts_with($currentRoute, 'profile') ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ str_starts_with($currentRoute, 'profile') ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Pengaturan Profil</span>
            </a>

            <!-- Panduan & SOP -->
            <a href="{{ route('panduan.index') }}" 
               @mouseenter="showTip($el, 'Panduan RBL & SOP', 'Helpdesk', 'bg-slate-700 text-slate-200 border border-slate-600')"
               @mouseleave="hideTip()"
               class="flex items-center rounded-xl transition-all relative group {{ $currentRoute === 'panduan.index' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:bg-slate-900/80 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto ' + ({{ $currentRoute === 'panduan.index' ? 'true' : 'false' }} ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25 ring-2 ring-orange-400/40' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white hover:scale-105') : 'gap-3 px-3.5 py-2.5 border-l-4 ' + ({{ $currentRoute === 'panduan.index' ? 'true' : 'false' }} ? 'border-orange-500 bg-slate-800 text-white shadow-xs' : 'border-transparent')">
                <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $currentRoute === 'panduan.index' ? 'text-inherit' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Panduan RBL & SOP</span>
            </a>

        </nav>
    </div>

    <!-- Bottom Actions -->
    <div class="p-2.5 border-t border-slate-800/90 bg-[#081120]">
        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    @mouseenter="showTip($el, 'Keluar Akun', 'Logout', 'bg-rose-500/20 text-rose-300 border border-rose-500/30')"
                    @mouseleave="hideTip()"
                    class="w-full flex items-center rounded-xl text-xs font-semibold text-slate-400 hover:bg-rose-500/10 hover:text-rose-400 hover:border-rose-500/20 transition-all border border-transparent group/logout relative focus:outline-none"
                    :class="sidebarCollapsed ? 'justify-center w-11 h-11 mx-auto hover:scale-105' : 'justify-center gap-2 px-3 py-2.5'">
                <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover/logout:scale-110 text-slate-400 group-hover/logout:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span x-show="!sidebarCollapsed" class="truncate overflow-hidden whitespace-nowrap">Keluar Akun</span>
            </button>
        </form>
    </div>

    <!-- Global Non-Clipping Fixed Floating Tooltip (Executive SaaS Style) -->
    <div 
        x-show="sidebarCollapsed && tooltip.visible" 
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-x-1 scale-95"
        x-transition:enter-end="opacity-100 translate-x-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-x-0 scale-100"
        x-transition:leave-end="opacity-0 translate-x-1 scale-95"
        :style="`top: ${tooltip.top}px;`"
        class="fixed left-20 ml-2.5 pointer-events-none z-50 flex items-center shadow-2xl transition-all duration-75">
        
        <!-- Arrow Indicator -->
        <div class="w-2.5 h-2.5 bg-[#0f243f] border-l border-b border-slate-700/90 transform rotate-45 -mr-1.5 z-10"></div>
        
        <!-- Tooltip Pill -->
        <div class="px-3.5 py-2 bg-[#0f243f]/95 backdrop-blur-md border border-slate-700/90 rounded-xl shadow-2xl text-xs flex items-center gap-2 whitespace-nowrap">
            <span class="font-semibold text-white tracking-wide" x-text="tooltip.title"></span>
            <template x-if="tooltip.badge">
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold" :class="tooltip.badgeClass" x-text="tooltip.badge"></span>
            </template>
            <template x-if="tooltip.shortcut">
                <span class="px-1.5 py-0.5 rounded bg-slate-800 text-slate-300 font-mono text-[9px] border border-slate-700/50" x-text="tooltip.shortcut"></span>
            </template>
        </div>
    </div>

</aside>
