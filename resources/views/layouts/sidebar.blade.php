@php
    $user = Auth::user();
    $currentRoute = Route::currentRouteName();

    // Hitung status stok real-time untuk badge sidebar
    $kritisCount = \App\Models\Product::where('status_stok', 'KRITIS')->count();
@endphp

<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-40 w-64 flex-shrink-0 bg-white border-r border-slate-200 transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col justify-between">
    
    <div class="flex flex-col flex-1 min-h-0">
        <!-- Logo Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-200">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img src="{{ asset('img/sanartex_horizontal.png') }}?v=4" alt="Logo Sanartex" class="h-11 sm:h-12 w-auto max-w-[195px] object-contain">
            </a>
            
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- User Quick Info -->
        <div class="px-6 py-3.5 border-b border-slate-100 bg-slate-50/70 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-slate-900 truncate">{{ $user->name ?? 'Pengguna' }}</p>
                <p class="text-[11px] text-slate-500 truncate">{{ $user->role_label ?? 'Staff' }}</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-3 space-y-1 flex-1 overflow-y-auto custom-scrollbar text-xs font-medium">
            
            <div class="px-2 pt-2 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Menu Utama
            </div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ $currentRoute === 'dashboard' ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                <svg class="w-4 h-4 flex-shrink-0 {{ $currentRoute === 'dashboard' ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span>Dashboard</span>
            </a>

            <!-- Master Produk Tekstil -->
            <a href="{{ route('products.index') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'products') ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                <svg class="w-4 h-4 flex-shrink-0 {{ str_starts_with($currentRoute, 'products') ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <span>Katalog Pakaian Jadi</span>
            </a>

            <div class="px-2 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Mutasi Persediaan
            </div>

            <!-- Stok Masuk -->
            <a href="{{ route('transaksi.masuk') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ $currentRoute === 'transaksi.masuk' ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                <svg class="w-4 h-4 flex-shrink-0 {{ $currentRoute === 'transaksi.masuk' ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                <span>Stok Masuk (Inbound)</span>
            </a>

            <!-- Stok Keluar -->
            <a href="{{ route('transaksi.keluar') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ $currentRoute === 'transaksi.keluar' ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                <svg class="w-4 h-4 flex-shrink-0 {{ $currentRoute === 'transaksi.keluar' ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12" /></svg>
                <span>Stok Keluar (Outbound)</span>
            </a>

            <div class="px-2 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Kontrol & Laporan
            </div>

            <!-- Analisis RBL Buffer -->
            <a href="{{ route('rbl.analisis') }}" 
               class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ $currentRoute === 'rbl.analisis' ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 flex-shrink-0 {{ $currentRoute === 'rbl.analisis' ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span>Analisis RBL</span>
                </div>
                @if($kritisCount > 0)
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">
                        {{ $kritisCount }}
                    </span>
                @endif
            </a>

            <!-- Laporan & Rekap -->
            <a href="{{ route('laporan.index') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ $currentRoute === 'laporan.index' ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                <svg class="w-4 h-4 flex-shrink-0 {{ $currentRoute === 'laporan.index' ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span>Laporan & Mutasi</span>
            </a>

            @if($user && $user->isSuperadmin())
                <div class="px-2 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Administrator
                </div>
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'users') ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ str_starts_with($currentRoute, 'users') ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span>Manajemen Pengguna</span>
                </a>
            @endif

            <div class="px-2 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Akun & Bantuan
            </div>
            <a href="{{ route('profile.index') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'profile') ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                <svg class="w-4 h-4 flex-shrink-0 {{ str_starts_with($currentRoute, 'profile') ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <span>Pengaturan Profil</span>
            </a>
            <a href="{{ route('panduan.index') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ $currentRoute === 'panduan.index' ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                <svg class="w-4 h-4 flex-shrink-0 {{ $currentRoute === 'panduan.index' ? 'text-orange-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span>Panduan RBL & SOP</span>
            </a>

        </nav>
    </div>

    <!-- Bottom Actions -->
    <div class="p-3 border-t border-slate-200">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span>Keluar Akun</span>
            </button>
        </form>
    </div>

</aside>
