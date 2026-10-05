@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Hak Akses Peran')

@section('content')
<div class="space-y-6" x-data="{ openCreateUser: false, editUser: null }">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-navy-900 tracking-tight">Manajemen Pengguna & Peran</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola akun dan otorisasi hak akses staf (Superadmin, Kepala Gudang, Admin Gudang, Purchasing).</p>
        </div>

        <button 
            @click="openCreateUser = true"
            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <form action="{{ route('users.index') }}" method="GET" data-ajax-filter="true" data-ajax-target="#ajax-table-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <!-- Search Keyword -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-[11px] font-semibold text-slate-700">Cari Pengguna</label>
                    <span class="ajax-live-badge"><span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realtime</span></span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Ketik nama staf, email, nomor HP..." 
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                </div>
            </div>

            <!-- Role Filter -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Peran (Role)</label>
                <select name="role" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <option value="all">Semua Peran</option>
                    <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    <option value="kepala_gudang" {{ request('role') == 'kepala_gudang' ? 'selected' : '' }}>Kepala Gudang</option>
                    <option value="admin_gudang" {{ request('role') == 'admin_gudang' ? 'selected' : '' }}>Admin Gudang</option>
                    <option value="purchasing" {{ request('role') == 'purchasing' ? 'selected' : '' }}>Purchasing</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('users.index') }}" data-ajax-reset="true" class="px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-medium text-xs rounded-lg border border-slate-200 transition-colors flex items-center justify-center" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table (Full Width) -->
    <div id="ajax-table-container" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Daftar Akun Pengguna Aktif</h3>
            <span class="text-xs text-slate-500">Total: {{ $users->total() }} User</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-semibold">
                    <tr>
                        <th class="py-3 px-4">Nama Pengguna</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Peran (Role)</th>
                        <th class="py-3 px-4">Nomor HP</th>
                        <th class="py-3 px-4">Terdaftar</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900 text-sm">{{ $u->name }}</div>
                                    @if(Auth::id() == $u->id)
                                        <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Akun Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $u->email }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $u->role_badge_class }}">
                                    {{ $u->role_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $u->phone ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $u->created_at->format('d M Y') }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button 
                                        @click="editUser = {{ json_encode($u) }}"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                                        title="Edit User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    @if(Auth::id() != $u->id)
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus akun pengguna ini?')">
                                             @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada akun pengguna yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3.5 border-t border-slate-200 bg-slate-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL TAMBAH USER -->
    <div x-show="openCreateUser" x-cloak @click.self="openCreateUser = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs" x-transition>
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-xl p-6 shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Tambah Pengguna Baru</h3>
                <button @click="openCreateUser = false" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="mt-4 space-y-3.5">
                @csrf
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Nama staf" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required placeholder="email@sanartex.com" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                        <option value="superadmin">👑 Superadmin</option>
                        <option value="kepala_gudang">👔 Kepala Gudang</option>
                        <option value="admin_gudang" selected>📦 Admin Gudang</option>
                        <option value="purchasing">🛒 Purchasing / Procurement</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nomor HP</label>
                    <input type="text" name="phone" placeholder="08xxxxxxxx" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="Min 6 karakter" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                    <button type="button" @click="openCreateUser = false" class="px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all shadow-xs">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT USER -->
    <template x-if="editUser">
        <div @click.self="editUser = null" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/50 backdrop-blur-xs">
            <div class="w-full max-w-md bg-white border border-slate-200 rounded-xl p-6 shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <h3 class="text-sm font-bold text-navy-900">Edit Pengguna</h3>
                    <button @click="editUser = null" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
                </div>

                <form :action="'/users/' + editUser.id" method="POST" class="mt-4 space-y-3.5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editUser.name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" x-model="editUser.email" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Peran (Role)</label>
                        <select name="role" x-model="editUser.role" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <option value="superadmin">👑 Superadmin</option>
                            <option value="kepala_gudang">👔 Kepala Gudang</option>
                            <option value="admin_gudang">📦 Admin Gudang</option>
                            <option value="purchasing">🛒 Purchasing / Procurement</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nomor HP</label>
                        <input type="text" name="phone" x-model="editUser.phone" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Ubah Kata Sandi (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                        <button type="button" @click="editUser = null" class="px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all shadow-xs">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
