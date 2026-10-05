@extends('layouts.app')

@section('title', 'Pengaturan Profil & Keamanan Akun')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" x-data="{ showPassCurrent: false, showPassNew: false, showPassConfirm: false }">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-navy-900 tracking-tight">Pengaturan Profil & Keamanan Akun</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi data diri, ganti kata sandi, dan tinjau hak akses otorisasi peran Anda.</p>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $user->role_badge_class }}">
                <span class="w-2 h-2 rounded-full bg-current opacity-75"></span>
                <span>Peran: {{ $user->role_label }}</span>
            </span>
        </div>
    </div>

    <!-- Hero Profile Overview Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-orange-100/40 via-sky-50/20 to-transparent rounded-full blur-2xl pointer-events-none -mr-16 -mt-16"></div>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 relative z-10">
            <!-- Big Avatar -->
            <div class="w-20 h-20 rounded-2xl bg-navy-900 text-white font-black text-2xl flex items-center justify-center shadow-md border-4 border-orange-100 flex-shrink-0 tracking-wider">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <!-- Identity Info -->
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2.5">
                    <h2 class="text-lg font-bold text-navy-900 truncate">{{ $user->name }}</h2>
                    <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full {{ $user->role_badge_class }}">
                        {{ $user->role_label }}
                    </span>
                    <span class="text-[10px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Akun Aktif
                    </span>
                </div>
                
                <p class="text-xs text-slate-600 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>{{ $user->email }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>{{ $user->phone ?? 'Belum ada nomor HP' }}</span>
                    </span>
                    <span class="flex items-center gap-1 text-slate-400">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Terdaftar: {{ $user->created_at->format('d F Y') }}</span>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Forms Grid: Profile Data & Security -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- 1. Form Informasi Profil -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-navy-900">Informasi Data Diri</h3>
                        <p class="text-[11px] text-slate-500">Perbarui nama lengkap dan kontak staf yang terdaftar.</p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $user->name) }}" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                        @error('name')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                        @error('email')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Nomor Handphone / WhatsApp
                        </label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone', $user->phone) }}" 
                            placeholder="Contoh: 081234567890" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                        @error('phone')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Peran Sistem (Otoritas)
                        </label>
                        <input 
                            type="text" 
                            disabled 
                            value="{{ $user->role_label }}" 
                            class="w-full px-3.5 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-xs text-slate-500 cursor-not-allowed font-medium">
                        <p class="text-[10px] text-slate-400 mt-1">Peran akun hanya dapat diubah oleh Superadmin melalui menu Pengguna.</p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs rounded-xl transition-all shadow-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Form Keamanan Kata Sandi -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-navy-900">Ubah Kata Sandi</h3>
                        <p class="text-[11px] text-slate-500">Pastikan akun Anda menggunakan kata sandi yang aman dan tidak mudah ditebak.</p>
                    </div>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassCurrent ? 'text' : 'password'" 
                                name="current_password" 
                                required 
                                placeholder="Masukkan sandi lama" 
                                class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                            <button 
                                type="button" 
                                @click="showPassCurrent = !showPassCurrent" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg x-show="!showPassCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassNew ? 'text' : 'password'" 
                                name="password" 
                                required 
                                placeholder="Minimal 6 karakter" 
                                class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                            <button 
                                type="button" 
                                @click="showPassNew = !showPassNew" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg x-show="!showPassNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Ulangi Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassConfirm ? 'text' : 'password'" 
                                name="password_confirmation" 
                                required 
                                placeholder="Ketik ulang kata sandi baru" 
                                class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                            <button 
                                type="button" 
                                @click="showPassConfirm = !showPassConfirm" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg x-show="!showPassConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 bg-navy-900 hover:bg-navy-950 active:bg-slate-900 text-white font-semibold text-xs rounded-xl transition-all shadow-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Role Access & Authorization Matrix Overview Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-navy-900">Hak Akses & Otoritas Peran ({{ $user->role_label }})</h3>
                    <p class="text-[11px] text-slate-500">Daftar wewenang modul yang dapat Anda akses pada sistem inventori tekstil SANARTEX.</p>
                </div>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $user->role_badge_class }}">
                {{ $user->role_label }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 text-xs">
            
            <!-- Modul Master Produk -->
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-2xs">
                    ✓
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Master Katalog Pakaian Jadi</div>
                    <p class="text-[11px] text-slate-500 mt-0.5">Melihat spesifikasi, buffer RBL, lokasi rak, dan barcode produk apparel.</p>
                </div>
            </div>

            <!-- Modul Stok Masuk Inbound -->
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-2xs">
                    ✓
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Penerimaan Stok Masuk</div>
                    <p class="text-[11px] text-slate-500 mt-0.5">Input surat jalan vendor, nomor batch produksi, dan scanner barcode.</p>
                </div>
            </div>

            <!-- Modul Stok Keluar Outbound -->
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-2xs">
                    ✓
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Pengeluaran Stok Keluar</div>
                    <p class="text-[11px] text-slate-500 mt-0.5">Distribusi produk ke outlet cabang / pesanan dengan validasi stok buffer.</p>
                </div>
            </div>

            <!-- Modul Analisis RBL -->
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-2xs">
                    ✓
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Analisis Buffer RBL (3 Zona)</div>
                    <p class="text-[11px] text-slate-500 mt-0.5">Monitoring stok kritis, overstock, dan kalkulasi draft reorder PO.</p>
                </div>
            </div>

            <!-- Modul Laporan Mutasi -->
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-2xs">
                    ✓
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Laporan Mutasi & Valuasi</div>
                    <p class="text-[11px] text-slate-500 mt-0.5">Rekapitulasi mutasi periode, nilai aset apparel, dan ekspor cetak.</p>
                </div>
            </div>

            <!-- Modul Pengguna (Superadmin Only) -->
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center {{ $user->isSuperadmin() ? 'text-emerald-600' : 'text-slate-400' }} flex-shrink-0 shadow-2xs font-bold">
                    {{ $user->isSuperadmin() ? '✓' : '🔒' }}
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Manajemen Staf & Pengguna</div>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        {{ $user->isSuperadmin() ? 'Kelola seluruh akun staf gudang & otorisasi hak akses peran.' : 'Fitur khusus peran Superadmin.' }}
                    </p>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
