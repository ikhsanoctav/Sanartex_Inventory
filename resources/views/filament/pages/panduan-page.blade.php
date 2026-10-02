<x-filament-panels::page>

<style>
/* ===== PANDUAN PAGE STYLES ===== */
.panduan-wrapper {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
    min-height: 80vh;
}

/* ---- Sidebar ---- */
.panduan-sidebar {
    width: 260px;
    flex-shrink: 0;
    position: sticky;
    top: 1rem;
}

.panduan-sidebar-inner {
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.07);
}

.panduan-sidebar-header {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    padding: 1rem 1.25rem;
    color: white;
    font-weight: 700;
    font-size: 0.8rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.panduan-nav-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.7rem 1.25rem;
    font-size: 0.875rem;
    color: #6b7280;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    transition: all 0.15s;
    border-left: 3px solid transparent;
    font-weight: 500;
}
.panduan-nav-item:hover {
    background: #f9fafb;
    color: #111827;
    border-left-color: #d1d5db;
}
.panduan-nav-item.active {
    background: #f0fdf4;
    color: #065f46;
    border-left-color: #10b981;
    font-weight: 600;
}
.panduan-nav-item svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}
.panduan-nav-divider {
    height: 1px;
    background: #f3f4f6;
    margin: 0.25rem 0;
}
.panduan-nav-group-label {
    padding: 0.6rem 1.25rem 0.25rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

/* ---- Content ---- */
.panduan-content {
    flex: 1;
    min-width: 0;
}

.panduan-section {
    display: none;
}
.panduan-section.active {
    display: block;
}

/* Cards */
.panduan-card {
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    padding: 1.75rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

.panduan-card-hero {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    border-radius: 0.75rem;
    padding: 2rem;
    margin-bottom: 1.25rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.panduan-card-hero::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.panduan-card-hero::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -20px;
    width: 260px; height: 260px;
    background: rgba(255,255,255,0.03);
    border-radius: 50%;
}

.panduan-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.3rem 0.65rem;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.badge-green  { background: #d1fae5; color: #065f46; }
.badge-blue   { background: #dbeafe; color: #1e40af; }
.badge-orange { background: #ffedd5; color: #c2410c; }
.badge-red    { background: #fee2e2; color: #991b1b; }
.badge-purple { background: #ede9fe; color: #5b21b6; }
.badge-gray   { background: #f3f4f6; color: #374151; }

.panduan-step {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #f3f4f6;
}
.panduan-step:last-child { border-bottom: none; }

.panduan-step-num {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.85rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.panduan-tip {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-left: 4px solid #10b981;
    border-radius: 0.5rem;
    padding: 0.9rem 1.1rem;
    font-size: 0.875rem;
    color: #065f46;
    margin-top: 0.75rem;
}
.panduan-tip-warn {
    background: #fff7ed;
    border: 1px solid #fed7aa;
    border-left: 4px solid #f97316;
    border-radius: 0.5rem;
    padding: 0.9rem 1.1rem;
    font-size: 0.875rem;
    color: #9a3412;
    margin-top: 0.75rem;
}
.panduan-tip-info {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-left: 4px solid #3b82f6;
    border-radius: 0.5rem;
    padding: 0.9rem 1.1rem;
    font-size: 0.875rem;
    color: #1e40af;
    margin-top: 0.75rem;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}
.feature-card {
    border: 1px solid #e5e7eb;
    border-radius: 0.6rem;
    padding: 1rem;
    background: #fafafa;
}
.feature-card-icon {
    width: 36px; height: 36px;
    border-radius: 0.5rem;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 0.6rem;
}

.flow-diagram {
    display: flex;
    align-items: center;
    gap: 0;
    flex-wrap: wrap;
    margin: 1.25rem 0;
}
.flow-box {
    padding: 0.6rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
    min-width: 110px;
}
.flow-arrow {
    font-size: 1.25rem;
    color: #9ca3af;
    padding: 0 0.25rem;
    flex-shrink: 0;
}

h2.section-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 0.35rem;
}
h3.subsection-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
p.section-desc {
    color: #6b7280;
    font-size: 0.9rem;
    margin-bottom: 1.25rem;
    line-height: 1.6;
}

.faq-item {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    margin-bottom: 0.6rem;
    overflow: hidden;
}
.faq-question {
    padding: 0.85rem 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #1f2937;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafafa;
    border: none;
    width: 100%;
    text-align: left;
}
.faq-question:hover { background: #f3f4f6; }
.faq-answer {
    padding: 0.85rem 1rem;
    font-size: 0.875rem;
    color: #4b5563;
    background: white;
    border-top: 1px solid #f3f4f6;
    line-height: 1.65;
}

kbd {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    padding: 0.15rem 0.4rem;
    font-size: 0.78rem;
    font-family: monospace;
    color: #374151;
}

table.panduan-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    margin-top: 0.75rem;
}
table.panduan-table th {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    padding: 0.6rem 0.9rem;
    text-align: left;
    font-weight: 700;
    color: #374151;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
table.panduan-table td {
    border: 1px solid #e5e7eb;
    padding: 0.65rem 0.9rem;
    color: #4b5563;
    vertical-align: top;
}
table.panduan-table tr:nth-child(even) td { background: #fafafa; }
</style>

<div class="panduan-wrapper" x-data="{ active: 'overview' }">

    {{-- ========== SIDEBAR ========== --}}
    <div class="panduan-sidebar">
        <div class="panduan-sidebar-inner">
            <div class="panduan-sidebar-header">📖 Daftar Panduan</div>

            <div class="panduan-nav-group-label">Mulai dari sini</div>
            <button class="panduan-nav-item" :class="{ active: active === 'overview' }" @click="active = 'overview'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                Gambaran Umum
            </button>
            <button class="panduan-nav-item" :class="{ active: active === 'alur' }" @click="active = 'alur'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" /></svg>
                Cara Kerja Sistem
            </button>

            <div class="panduan-nav-divider"></div>
            <div class="panduan-nav-group-label">Modul Sistem</div>

            <button class="panduan-nav-item" :class="{ active: active === 'dashboard' }" @click="active = 'dashboard'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                Dashboard
            </button>
            <button class="panduan-nav-item" :class="{ active: active === 'produk' }" @click="active = 'produk'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                Produk
            </button>
            <button class="panduan-nav-item" :class="{ active: active === 'stok-masuk' }" @click="active = 'stok-masuk'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" /></svg>
                Stok Masuk
            </button>
            <button class="panduan-nav-item" :class="{ active: active === 'stok-keluar' }" @click="active = 'stok-keluar'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" style="transform: rotate(180deg); transform-origin: center;" /></svg>
                Stok Keluar
            </button>
            <button class="panduan-nav-item" :class="{ active: active === 'laporan' }" @click="active = 'laporan'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                Laporan
            </button>

            <div class="panduan-nav-divider"></div>
            <div class="panduan-nav-group-label">Referensi</div>
            <button class="panduan-nav-item" :class="{ active: active === 'status' }" @click="active = 'status'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                Status Stok
            </button>
            <button class="panduan-nav-item" :class="{ active: active === 'faq' }" @click="active = 'faq'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                FAQ
            </button>
        </div>
    </div>

    {{-- ========== CONTENT AREA ========== --}}
    <div class="panduan-content">

        {{-- ========== GAMBARAN UMUM ========== --}}
        <div class="panduan-section" :class="{ active: active === 'overview' }">
            <div class="panduan-card-hero">
                <div style="position: relative; z-index: 10;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div style="background: rgba(255,255,255,0.15); border-radius: 0.6rem; padding: 0.6rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width:24px;height:24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; font-weight: 600; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.08em;">Selamat Datang di</div>
                            <h2 style="font-size: 1.5rem; font-weight: 800; color: white; margin: 0;">Panduan Pengguna Sanartex</h2>
                        </div>
                    </div>
                    <p style="color: rgba(255,255,255,0.75); font-size: 0.9rem; line-height: 1.7; max-width: 600px;">
                        Sistem Inventori Sanartex dirancang untuk membantu Anda mengelola stok barang secara <strong style="color:white;">real-time</strong>, akurat, dan efisien. Panduan ini akan memandu Anda memahami setiap fitur yang tersedia.
                    </p>
                    <div style="margin-top: 1.25rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <span class="panduan-badge" style="background: rgba(16,185,129,0.25); color: #a7f3d0; border: 1px solid rgba(16,185,129,0.3);">✓ Stok Real-time</span>
                        <span class="panduan-badge" style="background: rgba(59,130,246,0.25); color: #bfdbfe; border: 1px solid rgba(59,130,246,0.3);">✓ Laporan Otomatis</span>
                        <span class="panduan-badge" style="background: rgba(245,158,11,0.25); color: #fde68a; border: 1px solid rgba(245,158,11,0.3);">✓ Peringatan Stok Kritis</span>
                        <span class="panduan-badge" style="background: rgba(139,92,246,0.25); color: #ddd6fe; border: 1px solid rgba(139,92,246,0.3);">✓ Export CSV & PDF</span>
                    </div>
                </div>
            </div>

            <div class="panduan-card">
                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#10b981" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Fitur-Fitur Utama Sistem
                </h3>
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-card-icon" style="background: #d1fae5;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#065f46" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        </div>
                        <div style="font-weight: 700; font-size: 0.875rem; color: #111827; margin-bottom: 0.25rem;">Dashboard</div>
                        <div style="font-size: 0.8rem; color: #6b7280; line-height: 1.5;">Ringkasan stok, widget statistik, dan grafik pergerakan barang.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card-icon" style="background: #dbeafe;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#1e40af" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                        </div>
                        <div style="font-weight: 700; font-size: 0.875rem; color: #111827; margin-bottom: 0.25rem;">Manajemen Produk</div>
                        <div style="font-size: 0.8rem; color: #6b7280; line-height: 1.5;">Tambah, edit, hapus produk beserta kategori dan stok minimum.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card-icon" style="background: #d1fae5;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#065f46" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" /></svg>
                        </div>
                        <div style="font-weight: 700; font-size: 0.875rem; color: #111827; margin-bottom: 0.25rem;">Stok Masuk</div>
                        <div style="font-size: 0.8rem; color: #6b7280; line-height: 1.5;">Rekam penerimaan barang dari supplier atau retur pelanggan.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card-icon" style="background: #fee2e2;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#991b1b" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H16.5a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-9a2.25 2.25 0 01-2.25-2.25v-9a2.25 2.25 0 012.25-2.25H9m0 3l3 3m0 0l3-3m-3 3V3" /></svg>
                        </div>
                        <div style="font-weight: 700; font-size: 0.875rem; color: #111827; margin-bottom: 0.25rem;">Stok Keluar</div>
                        <div style="font-size: 0.8rem; color: #6b7280; line-height: 1.5;">Catat pengiriman atau penjualan barang keluar gudang.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card-icon" style="background: #ede9fe;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#5b21b6" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        </div>
                        <div style="font-weight: 700; font-size: 0.875rem; color: #111827; margin-bottom: 0.25rem;">Laporan</div>
                        <div style="font-size: 0.8rem; color: #6b7280; line-height: 1.5;">Rekap stok bulanan, unduh PDF & CSV untuk dokumentasi.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card-icon" style="background: #fff7ed;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#c2410c" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                        </div>
                        <div style="font-weight: 700; font-size: 0.875rem; color: #111827; margin-bottom: 0.25rem;">Notifikasi Kritis</div>
                        <div style="font-size: 0.8rem; color: #6b7280; line-height: 1.5;">Peringatan otomatis saat stok barang mencapai batas minimum.</div>
                    </div>
                </div>
            </div>

            <div class="panduan-card">
                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#3b82f6" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    Untuk Siapa Sistem Ini?
                </h3>
                <table class="panduan-table">
                    <thead>
                        <tr><th>Peran</th><th>Tugas Utama</th><th>Akses Fitur</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Admin Gudang</strong></td>
                            <td>Mencatat barang masuk & keluar setiap hari</td>
                            <td>Semua modul</td>
                        </tr>
                        <tr>
                            <td><strong>Manajer</strong></td>
                            <td>Memonitor stok dan mencetak laporan</td>
                            <td>Dashboard, Laporan</td>
                        </tr>
                        <tr>
                            <td><strong>Staff Operasional</strong></td>
                            <td>Input transaksi harian</td>
                            <td>Stok Masuk, Stok Keluar</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========== CARA KERJA SISTEM ========== --}}
        <div class="panduan-section" :class="{ active: active === 'alur' }">
            <div class="panduan-card">
                <h2 class="section-title">⚙️ Cara Kerja Sistem</h2>
                <p class="section-desc">Sistem inventori Sanartex bekerja dengan prinsip sederhana: setiap pergerakan barang (masuk atau keluar) dicatat secara real-time dan langsung memperbarui stok aktual produk secara otomatis.</p>

                <h3 class="subsection-title" style="margin-top:1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#8b5cf6" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" /></svg>
                    Alur Pergerakan Stok
                </h3>

                <div class="flow-diagram">
                    <div class="flow-box" style="background:#d1fae5; color:#065f46; border:2px solid #6ee7b7;">📦 Supplier /<br>Retur</div>
                    <div class="flow-arrow">→</div>
                    <div class="flow-box" style="background:#dbeafe; color:#1e40af; border:2px solid #93c5fd;">Form Stok<br>Masuk</div>
                    <div class="flow-arrow">→</div>
                    <div class="flow-box" style="background:#fef9c3; color:#854d0e; border:2px solid #fde047;">Sistem<br>Hitung Stok</div>
                    <div class="flow-arrow">→</div>
                    <div class="flow-box" style="background:#f3e8ff; color:#5b21b6; border:2px solid #d8b4fe;">Stok Aktual<br>Diperbarui</div>
                    <div class="flow-arrow">→</div>
                    <div class="flow-box" style="background:#fee2e2; color:#991b1b; border:2px solid #fca5a5;">Form Stok<br>Keluar</div>
                    <div class="flow-arrow">→</div>
                    <div class="flow-box" style="background:#f3f4f6; color:#374151; border:2px solid #d1d5db;">Ke<br>Pembeli</div>
                </div>

                <h3 class="subsection-title" style="margin-top:1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#f59e0b" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                    Bagaimana Stok Aktual Dihitung?
                </h3>
                <div style="background: #1f2937; border-radius: 0.6rem; padding: 1.25rem; font-family: monospace; color: #e5e7eb; font-size: 0.875rem; line-height: 2;">
                    <span style="color: #9ca3af;">// Rumus perhitungan stok aktual</span><br>
                    <span style="color: #fde68a;">Stok Aktual</span> = <span style="color: #6ee7b7;">Stok Awal</span> + <span style="color: #93c5fd;">Total Masuk</span> - <span style="color: #fca5a5;">Total Keluar</span><br><br>
                    <span style="color: #9ca3af;">// Status stok otomatis ditentukan oleh:</span><br>
                    <span style="color: #fde68a;">KRITIS</span>&nbsp;&nbsp;&nbsp; → Stok Aktual ≤ Stok Minimum<br>
                    <span style="color: #6ee7b7;">AMAN</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; → Stok Aktual &gt; Stok Minimum
                </div>

                <div class="panduan-tip" style="margin-top: 1rem;">
                    💡 <strong>Otomatis:</strong> Setiap kali Anda menyimpan transaksi masuk atau keluar, sistem langsung memperbarui stok aktual tanpa perlu input manual tambahan.
                </div>
            </div>

            <div class="panduan-card">
                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ef4444" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                    Sistem Peringatan Otomatis
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="border: 1px solid #fee2e2; border-radius: 0.5rem; padding: 1rem; background: #fff5f5;">
                        <div style="font-weight: 700; color: #991b1b; margin-bottom: 0.5rem; font-size: 0.875rem;">🔴 Stok KRITIS</div>
                        <div style="font-size: 0.8rem; color: #7f1d1d; line-height: 1.6;">Sistem menandai produk sebagai KRITIS dan menampilkan notifikasi di dashboard serta widget "Sisa SKU Kritis".</div>
                    </div>
                    <div style="border: 1px solid #d1fae5; border-radius: 0.5rem; padding: 1rem; background: #f0fdf4;">
                        <div style="font-weight: 700; color: #065f46; margin-bottom: 0.5rem; font-size: 0.875rem;">🟢 Stok AMAN</div>
                        <div style="font-size: 0.8rem; color: #064e3b; line-height: 1.6;">Produk berstatus AMAN artinya stok masih di atas batas minimum yang telah ditetapkan.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== DASHBOARD ========== --}}
        <div class="panduan-section" :class="{ active: active === 'dashboard' }">
            <div class="panduan-card">
                <h2 class="section-title">🏠 Panduan Dashboard</h2>
                <p class="section-desc">Dashboard adalah halaman utama yang muncul pertama kali saat Anda login. Dashboard menampilkan ringkasan kondisi stok secara menyeluruh dan real-time.</p>

                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#3b82f6" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                    Widget-Widget di Dashboard
                </h3>

                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#dbeafe; color:#1e40af;">1</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Widget Total Produk</div>
                        <div style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">Menampilkan jumlah total produk yang terdaftar dalam sistem. Klik untuk melihat daftar lengkap produk.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">2</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Widget Stok Masuk & Keluar (Hari Ini)</div>
                        <div style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">Menampilkan total unit yang masuk dan keluar pada hari ini. Data diperbarui otomatis setiap ada transaksi baru.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fee2e2; color:#991b1b;">3</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Widget SKU Kritis</div>
                        <div style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">Jumlah produk yang stoknya sudah menyentuh atau di bawah batas minimum. Segera lakukan pengisian stok untuk produk ini!</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#ede9fe; color:#5b21b6;">4</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Tombol Aksi Cepat</div>
                        <div style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;"><strong>Ekspor Laporan</strong> — langsung buka halaman laporan. <strong>Tambah Produk</strong> — buka form tambah produk baru.</div>
                    </div>
                </div>

                <div class="panduan-tip-info">
                    ℹ️ <strong>Tips:</strong> Dashboard diperbarui secara otomatis. Tidak perlu refresh halaman manual untuk melihat data terbaru.
                </div>
            </div>
        </div>

        {{-- ========== PRODUK ========== --}}
        <div class="panduan-section" :class="{ active: active === 'produk' }">
            <div class="panduan-card">
                <h2 class="section-title">📦 Panduan Produk</h2>
                <p class="section-desc">Modul Produk adalah tempat Anda mendaftarkan semua barang yang ada di gudang. Setiap produk memiliki informasi lengkap seperti kode, kategori, stok minimum, dan stok aktual.</p>

                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#10b981" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Cara Menambah Produk Baru
                </h3>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">1</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Buka Menu Produk</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Klik <strong>Produk</strong> di sidebar kiri, lalu klik tombol <strong>"New Product"</strong> atau <strong>"Tambah Produk"</strong> di pojok kanan atas.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">2</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Isi Informasi Produk</div>
                        <div style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">
                            Isi kolom-kolom berikut:<br>
                            • <strong>Nama Produk</strong> — nama lengkap barang<br>
                            • <strong>Kategori</strong> — kelompok/jenis produk<br>
                            • <strong>Satuan</strong> — unit ukuran (Pcs, Lusin, Kg, dll.)<br>
                            • <strong>Stok Minimum</strong> — batas stok sebelum dianggap KRITIS<br>
                            • <strong>Stok Awal</strong> — jumlah barang awal saat pertama didaftarkan
                        </div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">3</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Simpan Produk</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Klik tombol <strong>Save</strong>. Produk akan langsung muncul di daftar dan siap digunakan pada transaksi stok.</div>
                    </div>
                </div>

                <h3 class="subsection-title" style="margin-top: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#f59e0b" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    Cara Edit & Hapus Produk
                </h3>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fef9c3; color:#854d0e;">✏</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Edit Produk</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Pada baris produk di tabel, klik ikon <strong>pensil (edit)</strong> atau klik nama produknya. Ubah data yang perlu diperbarui, lalu klik Save.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fee2e2; color:#991b1b;">✕</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Hapus Produk</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Klik ikon <strong>hapus (trash)</strong> pada baris produk. Konfirmasi penghapusan pada dialog yang muncul.</div>
                    </div>
                </div>

                <div class="panduan-tip-warn">
                    ⚠️ <strong>Perhatian:</strong> Jangan hapus produk yang masih memiliki riwayat transaksi, karena akan menyebabkan data transaksi menjadi tidak lengkap. Sebaiknya nonaktifkan produk tersebut.
                </div>

                <h3 class="subsection-title" style="margin-top: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6b7280" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z" /></svg>
                    Fitur Pencarian & Filter
                </h3>
                <p style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">
                    Di halaman daftar produk tersedia fitur:<br>
                    • <strong>Kotak Pencarian</strong> — ketik nama produk untuk mencari dengan cepat<br>
                    • <strong>Filter Kategori</strong> — tampilkan produk berdasarkan kategori tertentu<br>
                    • <strong>Filter Status Stok</strong> — filter produk KRITIS atau AMAN<br>
                    • <strong>Sort/Urutkan</strong> — klik header kolom untuk mengurutkan data
                </p>
            </div>
        </div>

        {{-- ========== STOK MASUK ========== --}}
        <div class="panduan-section" :class="{ active: active === 'stok-masuk' }">
            <div class="panduan-card">
                <h2 class="section-title">📥 Panduan Stok Masuk</h2>
                <p class="section-desc">Stok Masuk digunakan untuk mencatat setiap penerimaan barang ke gudang, baik dari supplier, produksi sendiri, maupun retur dari pelanggan.</p>

                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#10b981" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Langkah-Langkah Input Stok Masuk
                </h3>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">1</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Buka Menu Stok Masuk</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Di sidebar, klik grup <strong>Transaksi</strong> → pilih <strong>Stok Masuk</strong>.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">2</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Isi Tanggal Transaksi</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Pilih tanggal saat barang diterima. Secara default terisi tanggal hari ini. Anda bisa mengubahnya jika input mundur.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">3</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Pilih Produk</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Klik dropdown <strong>"Pilih Produk"</strong> dan ketik nama produk untuk mencari. Pilih produk yang barangnya datang.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">4</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Isi Jumlah (QTY)</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Masukkan jumlah unit barang yang diterima. Kolom <strong>Satuan</strong> terisi otomatis sesuai produk.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">5</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Isi Sumber Barang</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Isi kolom <strong>Sumber Barang</strong> dengan keterangan asal barang. Contoh: <em>"Supplier PT Maju Bersama"</em> atau <em>"Retur Customer"</em>.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#d1fae5; color:#065f46;">6</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Simpan Transaksi</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Klik tombol <strong>"Simpan Stok Masuk"</strong>. Stok aktual produk akan langsung bertambah dan muncul di riwayat sebelah kanan.</div>
                    </div>
                </div>

                <div class="panduan-tip">
                    💡 <strong>Tips:</strong> Setelah menyimpan, form akan otomatis dikosongkan kembali sehingga Anda bisa langsung input transaksi berikutnya.
                </div>

                <h3 class="subsection-title" style="margin-top: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6b7280" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                    Riwayat Stok Masuk
                </h3>
                <p style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">
                    Di kolom kanan halaman Stok Masuk terdapat tabel <strong>Riwayat Stok Masuk</strong> yang menampilkan 5 transaksi terbaru. Klik <strong>"Lihat Semua"</strong> untuk melihat seluruh riwayat beserta tombol hapus riwayat.
                </p>

                <h3 class="subsection-title" style="margin-top: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6b7280" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Export CSV Stok Masuk
                </h3>
                <p style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">
                    Klik tombol <strong>"Export CSV"</strong> di pojok kanan atas untuk mengunduh seluruh riwayat stok masuk dalam format CSV yang bisa dibuka di Excel.
                </p>
            </div>
        </div>

        {{-- ========== STOK KELUAR ========== --}}
        <div class="panduan-section" :class="{ active: active === 'stok-keluar' }">
            <div class="panduan-card">
                <h2 class="section-title">📤 Panduan Stok Keluar</h2>
                <p class="section-desc">Stok Keluar digunakan untuk mencatat setiap pengeluaran barang dari gudang, baik untuk penjualan, pengiriman ke cabang, maupun keperluan internal.</p>

                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ef4444" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Langkah-Langkah Input Stok Keluar
                </h3>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fee2e2; color:#991b1b;">1</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Buka Menu Stok Keluar</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Di sidebar, klik grup <strong>Transaksi</strong> → pilih <strong>Stok Keluar</strong>.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fee2e2; color:#991b1b;">2</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Isi Tanggal Transaksi</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Pilih tanggal saat barang keluar dari gudang. Default adalah hari ini.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fee2e2; color:#991b1b;">3</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Pilih Produk</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Pilih produk yang akan dikeluarkan. Pastikan produk memiliki stok yang cukup sebelum menyimpan.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fee2e2; color:#991b1b;">4</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Isi Jumlah (QTY)</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Masukkan jumlah barang yang keluar. <strong>Sistem akan memvalidasi</strong> bahwa jumlah tidak melebihi stok yang tersedia.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fee2e2; color:#991b1b;">5</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Isi Tujuan Barang</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Isi kolom <strong>Tujuan Barang</strong> untuk mencatat kemana barang dikirim. Contoh: <em>"Penjualan Toko Pusat"</em> atau <em>"Pengiriman Cabang Jakarta"</em>.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#fee2e2; color:#991b1b;">6</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Simpan Transaksi</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Klik <strong>"Simpan Stok Keluar"</strong>. Stok aktual produk berkurang sesuai jumlah yang diinput.</div>
                    </div>
                </div>

                <div class="panduan-tip-warn">
                    ⚠️ <strong>Penting:</strong> Jika jumlah yang dimasukkan melebihi stok tersedia, sistem akan menampilkan pesan error dan transaksi tidak akan tersimpan. Pastikan stok mencukupi sebelum menyimpan.
                </div>

                <div class="panduan-tip">
                    💡 <strong>Cek Stok Kritis:</strong> Setelah transaksi keluar, periksa widget "Sisa SKU Kritis" di dashboard atau halaman Stok Keluar. Jika muncul angka > 0, segera lakukan pengisian stok.
                </div>
            </div>
        </div>

        {{-- ========== LAPORAN ========== --}}
        <div class="panduan-section" :class="{ active: active === 'laporan' }">
            <div class="panduan-card">
                <h2 class="section-title">📊 Panduan Laporan</h2>
                <p class="section-desc">Modul Laporan menyediakan rekap pergerakan stok dalam rentang waktu tertentu, dilengkapi fitur cetak dan unduh dalam format PDF maupun CSV.</p>

                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#8b5cf6" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    Cara Mengatur Periode Laporan
                </h3>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#ede9fe; color:#5b21b6;">1</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Buka Menu Laporan</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Klik <strong>Laporan</strong> di sidebar. Halaman akan menampilkan laporan bulan ini secara default.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#ede9fe; color:#5b21b6;">2</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Atur Filter Tanggal</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Gunakan filter <strong>Tanggal Mulai</strong> dan <strong>Tanggal Akhir</strong> untuk menentukan periode laporan yang diinginkan.</div>
                    </div>
                </div>
                <div class="panduan-step">
                    <div class="panduan-step-num" style="background:#ede9fe; color:#5b21b6;">3</div>
                    <div>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.3rem;">Lihat Rekap Stok</div>
                        <div style="font-size: 0.875rem; color: #4b5563;">Tabel laporan menampilkan untuk setiap produk: <strong>Stok Awal → Masuk → Keluar → Stok Akhir</strong> dalam periode tersebut.</div>
                    </div>
                </div>

                <h3 class="subsection-title" style="margin-top: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6b7280" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Cara Download Laporan
                </h3>
                <table class="panduan-table">
                    <thead>
                        <tr><th>Format</th><th>Tombol</th><th>Kegunaan</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="panduan-badge badge-red">PDF</span></td>
                            <td><strong>Download PDF</strong></td>
                            <td>Laporan siap cetak dengan format yang rapi. Ideal untuk presentasi atau pengarsipan resmi.</td>
                        </tr>
                        <tr>
                            <td><span class="panduan-badge badge-green">CSV</span></td>
                            <td><strong>Export CSV</strong> (di halaman Stok Masuk/Keluar)</td>
                            <td>Data mentah yang bisa dibuka di Microsoft Excel untuk analisis lebih lanjut.</td>
                        </tr>
                        <tr>
                            <td><span class="panduan-badge badge-gray">Print</span></td>
                            <td><strong>Cetak Laporan</strong></td>
                            <td>Langsung mencetak laporan menggunakan printer yang terhubung.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="panduan-tip">
                    💡 <strong>Tips Laporan Bulanan:</strong> Biasakan mengunduh dan menyimpan laporan setiap akhir bulan sebagai backup data inventori.
                </div>
            </div>
        </div>

        {{-- ========== STATUS STOK ========== --}}
        <div class="panduan-section" :class="{ active: active === 'status' }">
            <div class="panduan-card">
                <h2 class="section-title">🏷️ Referensi Status Stok</h2>
                <p class="section-desc">Sistem secara otomatis menentukan status stok berdasarkan perbandingan antara stok aktual dengan stok minimum yang ditetapkan untuk setiap produk.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="border: 2px solid #6ee7b7; border-radius: 0.75rem; padding: 1.25rem; background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                        <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.75rem;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #10b981;"></div>
                            <span style="font-weight: 800; font-size: 1rem; color: #065f46;">AMAN</span>
                        </div>
                        <div style="font-size: 0.875rem; color: #047857; line-height: 1.65;">
                            Stok aktual <strong>lebih besar</strong> dari stok minimum.<br><br>
                            <em>Contoh: Stok minimum 10, stok aktual 45 → AMAN</em>
                        </div>
                        <div style="margin-top: 0.75rem; font-size: 0.8rem; color: #065f46; background: rgba(16,185,129,0.1); border-radius: 0.35rem; padding: 0.4rem 0.6rem;">
                            ✅ Tidak perlu tindakan segera
                        </div>
                    </div>
                    <div style="border: 2px solid #fca5a5; border-radius: 0.75rem; padding: 1.25rem; background: linear-gradient(135deg, #fff5f5, #fee2e2);">
                        <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.75rem;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444;"></div>
                            <span style="font-weight: 800; font-size: 1rem; color: #991b1b;">KRITIS</span>
                        </div>
                        <div style="font-size: 0.875rem; color: #b91c1c; line-height: 1.65;">
                            Stok aktual <strong>kurang dari atau sama dengan</strong> stok minimum.<br><br>
                            <em>Contoh: Stok minimum 10, stok aktual 3 → KRITIS</em>
                        </div>
                        <div style="margin-top: 0.75rem; font-size: 0.8rem; color: #991b1b; background: rgba(239,68,68,0.1); border-radius: 0.35rem; padding: 0.4rem 0.6rem;">
                            🚨 Segera lakukan pengisian stok!
                        </div>
                    </div>
                </div>

                <h3 class="subsection-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6b7280" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                    Cara Mengatur Stok Minimum
                </h3>
                <p style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">
                    1. Buka menu <strong>Produk</strong><br>
                    2. Klik produk yang ingin diatur<br>
                    3. Ubah nilai <strong>Stok Minimum</strong> sesuai kebutuhan<br>
                    4. Klik <strong>Save</strong><br><br>
                    Sistem akan langsung memperbarui status stok produk tersebut berdasarkan nilai minimum yang baru.
                </p>
            </div>
        </div>

        {{-- ========== FAQ ========== --}}
        <div class="panduan-section" :class="{ active: active === 'faq' }">
            <div class="panduan-card">
                <h2 class="section-title">❓ Pertanyaan yang Sering Ditanyakan</h2>
                <p class="section-desc">Temukan jawaban untuk pertanyaan umum seputar penggunaan sistem inventori Sanartex.</p>

                <div x-data="{ open: null }">

                    <div class="faq-item">
                        <button class="faq-question" @click="open = open === 1 ? null : 1">
                            Mengapa stok aktual tidak berubah setelah input transaksi?
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;" :class="{ 'rotate-180': open === 1 }" style="transition: transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div class="faq-answer" x-show="open === 1">
                            Pastikan Anda mengklik tombol <strong>"Simpan"</strong> setelah mengisi form. Stok hanya diperbarui setelah transaksi berhasil disimpan (muncul notifikasi hijau berhasil). Jika ada validasi yang gagal, akan muncul pesan error merah.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" @click="open = open === 2 ? null : 2">
                            Apakah bisa input transaksi dengan tanggal yang lampau?
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div class="faq-answer" x-show="open === 2">
                            Ya, bisa. Pada form Stok Masuk atau Stok Keluar, klik kolom <strong>Tanggal Transaksi</strong> dan pilih tanggal yang diinginkan dari kalender. Sistem akan menyimpan transaksi dengan tanggal tersebut.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" @click="open = open === 3 ? null : 3">
                            Bagaimana cara menghapus transaksi yang salah input?
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div class="faq-answer" x-show="open === 3">
                            Untuk koreksi transaksi yang salah, Anda bisa membuat <strong>transaksi lawan</strong>: jika salah input stok masuk, input stok keluar dengan jumlah yang sama pada produk yang sama. Atau gunakan fitur <strong>"Clear Semua Riwayat"</strong> (hati-hati, ini menghapus semua data!).
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" @click="open = open === 4 ? null : 4">
                            Mengapa muncul error "Jumlah melebihi stok tersedia" saat input stok keluar?
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div class="faq-answer" x-show="open === 4">
                            Ini berarti jumlah yang Anda masukkan <strong>melebihi stok aktual</strong> produk tersebut. Sistem secara otomatis mencegah stok menjadi negatif. Periksa stok tersedia di halaman Produk, lalu sesuaikan jumlah yang akan dikeluarkan.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" @click="open = open === 5 ? null : 5">
                            Bagaimana cara menetapkan batas stok minimum agar peringatan KRITIS muncul?
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div class="faq-answer" x-show="open === 5">
                            Buka menu <strong>Produk</strong> → pilih produk → edit kolom <strong>Stok Minimum</strong> → Save. Sistem akan otomatis menandai produk sebagai KRITIS saat stok aktual ≤ stok minimum.
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" @click="open = open === 6 ? null : 6">
                            File CSV dari Export bisa dibuka di Excel?
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div class="faq-answer" x-show="open === 6">
                            Ya. File CSV dapat dibuka langsung di Microsoft Excel, Google Sheets, atau aplikasi spreadsheet lainnya. Di Excel: klik kanan file CSV → <em>Open with</em> → <em>Microsoft Excel</em>. Atau buka Excel, pilih <em>Data</em> → <em>From Text/CSV</em>.
                        </div>
                    </div>

                </div>

                <div class="panduan-tip-info" style="margin-top: 1.5rem;">
                    ℹ️ Masih ada pertanyaan? Hubungi tim IT atau administrator sistem Anda untuk bantuan lebih lanjut.
                </div>
            </div>
        </div>

    </div>{{-- end panduan-content --}}
</div>{{-- end panduan-wrapper --}}

</x-filament-panels::page>
