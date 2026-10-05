<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth bg-slate-900 text-slate-800 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SANARTEX - Sistem Inventori Pakaian Jadi Berbasis RBL</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            900: '#134e4a',
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(148, 163, 184, 0.25) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .text-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #334155 50%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .text-gradient-accent {
            background: linear-gradient(135deg, #0284c7 0%, #0d9488 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 selection:bg-indigo-500 selection:text-white" 
      x-data="{ 
          simItem: 'Hoodie Oversize Cotton Fleece Hitam',
          simStok: 12, 
          simMin: 25, 
          simMax: 120,
          simSatuan: 'Pcs',
          simHarga: 185000,
          setPreset(name, stok, min, max, satuan, harga) {
              this.simItem = name;
              this.simStok = stok;
              this.simMin = min;
              this.simMax = max;
              this.simSatuan = satuan;
              this.simHarga = harga;
          }
      }">

    <!-- Top Ambient Glow -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-indigo-100/60 via-teal-50/40 to-transparent pointer-events-none -z-10 blur-3xl"></div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Brand Logo (Horizontal Lockup - Prominent) -->
            <a href="/" class="flex items-center group">
                <img src="{{ asset('img/sanartex_horizontal.png') }}?v=4" alt="Logo Sanartex" class="h-11 sm:h-12 lg:h-13 w-auto object-contain group-hover:opacity-90 transition-all">
            </a>

            <!-- Nav Center Links -->
            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#fitur" class="hover:text-slate-900 transition-colors">Fitur Unggulan</a>
                <a href="#rbl-simulator" class="hover:text-slate-900 transition-colors flex items-center gap-1">
                    <span>Simulasi RBL</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-ping"></span>
                </a>
                <a href="#peran" class="hover:text-slate-900 transition-colors">Akses Peran</a>
                <a href="{{ route('panduan.index') }}" class="hover:text-slate-900 transition-colors">Panduan SOP</a>
            </nav>

            <!-- Action Button -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs shadow-sm transition-all hover:shadow hover:-translate-y-0.5">
                        <span>Buka Dashboard</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs shadow-sm transition-all hover:shadow hover:-translate-y-0.5">
                        <span>Masuk ke Portal</span>
                        <svg class="w-3.5 h-3.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-12 pb-20 lg:pt-16 lg:pb-28 overflow-hidden bg-grid-pattern border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto">
                <!-- Pill Tag -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 shadow-xs text-xs font-semibold text-slate-700 mb-6 animate-float">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Sistem Kontrol Buffer Inventori Pakaian Jadi Berbasis Rule-Based Logic</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.15]">
                    Kendali Presisi Stok Pakaian Jadi dengan 
                    <span class="text-gradient-accent">3-Tier Buffer Level</span>
                </h1>

                <!-- Subtitle -->
                <p class="mt-5 text-sm sm:text-base lg:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                    Eliminasi risiko kehabisan produk unggulan (hoodie, kaos, kemeja, jaket) dan cegah overstock di rak gudang melalui evaluasi otomatis 3 Zona RBL dan generator restock PO konveksi cerdas.
                </p>

                <!-- CTA Buttons -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3.5">
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm shadow-sm hover:shadow-md transition-all flex items-center gap-2">
                        <span>Akses Portal Inventori</span>
                        <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#rbl-simulator" class="px-6 py-3 rounded-xl bg-white hover:bg-slate-100 text-slate-800 font-semibold text-xs sm:text-sm border border-slate-300 shadow-xs transition-all flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                        <span>Uji Simulasi RBL</span>
                    </a>
                </div>

                <!-- 3 Key Metric Badges -->
                <div class="mt-10 grid grid-cols-3 gap-3 max-w-xl mx-auto text-left">
                    <div class="p-3 bg-white/90 backdrop-blur rounded-xl border border-slate-200 shadow-xs">
                        <span class="block text-[11px] font-bold text-rose-600 uppercase tracking-wider">🔴 Zona Kritis</span>
                        <p class="text-[11px] text-slate-500 mt-0.5">Stok &le; Min &rarr; Alert PO Cepat</p>
                    </div>
                    <div class="p-3 bg-white/90 backdrop-blur rounded-xl border border-slate-200 shadow-xs">
                        <span class="block text-[11px] font-bold text-emerald-600 uppercase tracking-wider">🟢 Zona Normal</span>
                        <p class="text-[11px] text-slate-500 mt-0.5">Min &lt; Stok &le; Max &rarr; Ideal</p>
                    </div>
                    <div class="p-3 bg-white/90 backdrop-blur rounded-xl border border-slate-200 shadow-xs">
                        <span class="block text-[11px] font-bold text-blue-600 uppercase tracking-wider">🔵 Zona Berlebih</span>
                        <p class="text-[11px] text-slate-500 mt-0.5">Stok &gt; Max &rarr; Tahan PO</p>
                    </div>
                </div>
            </div>

            <!-- UI Mockup Showcase Window -->
            <div class="mt-14 max-w-5xl mx-auto">
                <div class="rounded-2xl bg-slate-900 p-2 sm:p-3 shadow-2xl border border-slate-800">
                    
                    <!-- Window Top Bar -->
                    <div class="flex items-center justify-between px-3 py-2 border-b border-slate-800 text-slate-400 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500/80 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                            <span class="ml-2 text-[11px] font-mono text-slate-400 hidden sm:inline">sanartex-app.internal/dashboard</span>
                        </div>
                        <span class="text-[10px] font-semibold bg-slate-800 px-2.5 py-0.5 rounded text-teal-300">Live Apparel Inventory Radar</span>
                    </div>

                    <!-- Inner Mockup Screen -->
                    <div class="bg-slate-50 rounded-xl p-4 sm:p-6 text-xs overflow-hidden">
                        
                        <!-- Top KPI Row -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] text-slate-500 font-semibold uppercase">Total SKU Aktif</span>
                                <div class="text-lg font-bold text-slate-900 mt-0.5">12 Produk</div>
                                <span class="text-[10px] text-teal-600 font-medium">&bull; Katun, Denim, Rayon</span>
                            </div>
                            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] text-rose-600 font-bold uppercase">🔴 Stok Kritis</span>
                                <div class="text-lg font-bold text-rose-600 mt-0.5">3 SKU</div>
                                <span class="text-[10px] text-rose-500 font-medium">&bull; Perlu Reorder Darurat</span>
                            </div>
                            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] text-emerald-600 font-bold uppercase">🟢 Stok Normal</span>
                                <div class="text-lg font-bold text-emerald-600 mt-0.5">7 SKU</div>
                                <span class="text-[10px] text-emerald-600 font-medium">&bull; Persediaan Aman</span>
                            </div>
                            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] text-blue-600 font-bold uppercase">🔵 Stok Berlebih</span>
                                <div class="text-lg font-bold text-blue-600 mt-0.5">2 SKU</div>
                                <span class="text-[10px] text-blue-500 font-medium">&bull; Tahan Pengadaan</span>
                            </div>
                        </div>

                        <!-- Mini Table Sample -->
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between">
                                <span class="font-bold text-slate-900">Monitoring Buffer Persediaan Pakaian Jadi</span>
                                <span class="text-[10px] text-slate-500">Real-time Rule-Based Logic</span>
                            </div>
                            <div class="divide-y divide-slate-100">
                                
                                <!-- Row 1 (Kritis) -->
                                <div class="p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 hover:bg-slate-50/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 font-bold flex items-center justify-center text-xs">
                                            01
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">Hoodie Oversize Cotton Fleece (Hitam)</div>
                                            <div class="text-[11px] text-slate-500">SKU: AP-HOD-001 &bull; Rak Apparel 01 &bull; Vendor: PT Konveksi Garment Mandiri</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <span class="text-xs font-extrabold text-rose-600">8 Pcs</span>
                                            <span class="text-[10px] text-slate-400 block">Min: 25 | Max: 120</span>
                                        </div>
                                        <span class="px-2 py-1 rounded text-[10px] font-extrabold bg-rose-100 text-rose-700 border border-rose-200">
                                            KRITIS (Order +112)
                                        </span>
                                    </div>
                                </div>

                                <!-- Row 2 (Normal) -->
                                <div class="p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 hover:bg-slate-50/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold flex items-center justify-center text-xs">
                                            02
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">Kaos Polos Heavyweight 24s (Hitam Solid)</div>
                                            <div class="text-[11px] text-slate-500">SKU: AP-TSH-001 &bull; Rak Apparel 02 &bull; Vendor: PT Citra Kaos Indonesia</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <span class="text-xs font-extrabold text-emerald-600">65 Pcs</span>
                                            <span class="text-[10px] text-slate-400 block">Min: 30 | Max: 150</span>
                                        </div>
                                        <span class="px-2 py-1 rounded text-[10px] font-extrabold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            NORMAL (Aman)
                                        </span>
                                    </div>
                                </div>

                                <!-- Row 3 (Berlebih) -->
                                <div class="p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 hover:bg-slate-50/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 font-bold flex items-center justify-center text-xs">
                                            03
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">Jaket Coach Taslan Windbreaker (Navy)</div>
                                            <div class="text-[11px] text-slate-500">SKU: AP-JKT-001 &bull; Rak Apparel 04 &bull; Vendor: PT Outerwear Prima Mandiri</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <span class="text-xs font-extrabold text-blue-600">95 Pcs</span>
                                            <span class="text-[10px] text-slate-400 block">Min: 15 | Max: 60</span>
                                        </div>
                                        <span class="px-2 py-1 rounded text-[10px] font-extrabold bg-blue-100 text-blue-700 border border-blue-200">
                                            BERLEBIH (Tahan PO)
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Interactive Live RBL Simulator Section -->
    <section id="rbl-simulator" class="py-20 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-10">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-semibold border border-teal-200 mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Simulasi Interaktif RBL</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Uji Logika Penentuan Buffer Level Secara Langsung
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-2">
                    Pilih preset produk apparel atau geser slider stok untuk melihat respon seketika dari mesin evaluasi 3 Zona RBL dan perhitungan rekomendasi restock PO.
                </p>
            </div>

            <!-- Preset Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-2 mb-8">
                <span class="text-xs font-semibold text-slate-500 mr-2">Pilih Sampel Apparel:</span>
                <button 
                    @click="setPreset('Hoodie Oversize Cotton Fleece Hitam', 8, 25, 120, 'Pcs', 185000)"
                    :class="simItem === 'Hoodie Oversize Cotton Fleece Hitam' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all">
                    🔴 Hoodie Oversize (Kritis)
                </button>
                <button 
                    @click="setPreset('Kaos Heavyweight 24s Hitam', 65, 30, 150, 'Pcs', 75000)"
                    :class="simItem === 'Kaos Heavyweight 24s Hitam' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all">
                    🟢 Kaos 24s (Normal)
                </button>
                <button 
                    @click="setPreset('Jaket Coach Windbreaker Navy', 95, 15, 60, 'Pcs', 210000)"
                    :class="simItem === 'Jaket Coach Windbreaker Navy' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all">
                    🔵 Jaket Coach (Berlebih)
                </button>
            </div>

            <!-- Simulator Engine Box -->
            <div class="p-6 sm:p-8 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm space-y-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-200">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Produk Yang Disimulasikan</span>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900" x-text="simItem"></h3>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <div class="text-slate-600">
                            Batas Min: <strong class="text-slate-900 font-bold" x-text="simMin + ' ' + simSatuan"></strong>
                        </div>
                        <div class="text-slate-600">
                            Batas Max: <strong class="text-slate-900 font-bold" x-text="simMax + ' ' + simSatuan"></strong>
                        </div>
                    </div>
                </div>

                <!-- Slider Control -->
                <div>
                    <div class="flex justify-between items-center text-xs font-bold text-slate-700 mb-2">
                        <span>Geser Kuantitas Stok Aktual:</span>
                        <span class="text-base font-extrabold text-slate-900 px-3 py-1 bg-white rounded-lg border border-slate-200 shadow-2xs" 
                              x-text="simStok + ' ' + simSatuan"></span>
                    </div>
                    <input 
                        type="range" 
                        min="0" 
                        :max="Math.round(simMax * 1.5)" 
                        x-model.number="simStok" 
                        class="w-full h-3 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-slate-900">
                </div>

                <!-- Visual 3-Zone Bar -->
                <div>
                    <div class="w-full h-5 rounded-full bg-slate-200 flex overflow-hidden relative shadow-inner">
                        <!-- Critical Segment (0 to Min) -->
                        <div class="h-full bg-rose-500 flex items-center justify-center text-[9px] font-bold text-white tracking-wider" 
                             :style="'width: ' + ((simMin / (simMax * 1.5)) * 100) + '%'">
                            KRITIS
                        </div>
                        <!-- Normal Segment (Min to Max) -->
                        <div class="h-full bg-emerald-500 flex items-center justify-center text-[9px] font-bold text-white tracking-wider" 
                             :style="'width: ' + (((simMax - simMin) / (simMax * 1.5)) * 100) + '%'">
                            NORMAL
                        </div>
                        <!-- Overstock Segment (> Max) -->
                        <div class="h-full bg-blue-500 flex items-center justify-center text-[9px] font-bold text-white tracking-wider" 
                             :style="'width: ' + (((simMax * 1.5 - simMax) / (simMax * 1.5)) * 100) + '%'">
                            BERLEBIH
                        </div>
                        
                        <!-- Needle Indicator -->
                        <div class="absolute top-0 bottom-0 w-2 bg-slate-900 shadow-md rounded-full -translate-x-1/2 transition-all duration-100"
                             :style="'left: ' + Math.min(100, Math.max(0, (simStok / (simMax * 1.5)) * 100)) + '%'"></div>
                    </div>

                    <div class="flex justify-between text-[11px] text-slate-500 mt-2 font-semibold">
                        <span x-text="'0 ' + simSatuan"></span>
                        <span x-text="'Min: ' + simMin + ' ' + simSatuan"></span>
                        <span x-text="'Max: ' + simMax + ' ' + simSatuan"></span>
                        <span x-text="'Cap: ' + Math.round(simMax * 1.5) + ' ' + simSatuan"></span>
                    </div>
                </div>

                <!-- Dynamic Status Outcome Box -->
                <div class="p-5 rounded-xl border transition-colors duration-200"
                     :class="{
                        'bg-rose-50/80 border-rose-200 text-rose-950': simStok <= simMin,
                        'bg-emerald-50/80 border-emerald-200 text-emerald-950': simStok > simMin && simStok <= simMax,
                        'bg-blue-50/80 border-blue-200 text-blue-950': simStok > simMax
                     }">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xl" x-text="simStok <= simMin ? '🚨' : (simStok <= simMax ? '✅' : '📦')"></span>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider opacity-70">Hasil Klasifikasi RBL</span>
                                <h4 class="text-sm font-extrabold" 
                                    x-text="simStok <= simMin ? 'ZONA MERAH - KRITIS (Stok Di Bawah Batas Minimum)' : (simStok <= simMax ? 'ZONA HIJAU - NORMAL (Persediaan Ideal & Aman)' : 'ZONA BIRU - BERLEBIH (Melebihi Kapasitas Maksimal)')"></h4>
                            </div>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider opacity-70">Saran Order Pengadaan (PO)</span>
                            <div class="text-base font-extrabold" 
                                 x-text="simStok <= simMin ? '+' + (simMax - simStok) + ' ' + simSatuan : '0 ' + simSatuan + ' (Tidak Perlu Order)'"></div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-current/10 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="font-bold block mb-0.5">Rekomendasi Tindakan Operasional:</span>
                            <p class="opacity-90 leading-relaxed" 
                               x-text="simStok <= simMin ? 'Segera terbitkan Purchase Order ke supplier konveksi. Stok berisiko habis sebelum lead time selesai.' : (simStok <= simMax ? 'Stok berada dalam kondisi prima untuk melayani pesanan outlet & marketplace.' : 'Tahan pengadaan baru dari vendor untuk mencegah modal mati dan penumpukan gudang.')"></p>
                        </div>
                        <div class="bg-white/80 rounded-lg p-2.5 border border-current/10 text-slate-800">
                            <div class="flex justify-between text-[11px] mb-1">
                                <span class="text-slate-500">Estimasi Biaya PO:</span>
                                <span class="font-bold text-slate-900" 
                                      x-text="simStok <= simMin ? 'Rp ' + ((simMax - simStok) * simHarga).toLocaleString('id-ID') : 'Rp 0'"></span>
                            </div>
                            <div class="flex justify-between text-[11px]">
                                <span class="text-slate-500">Estimasi Lead Time:</span>
                                <span class="font-bold text-slate-900">7 - 14 Hari Kerja</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- 4 Core Capabilities Section -->
    <section id="fitur" class="py-20 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-14">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-200/80 text-slate-700 text-xs font-semibold mb-3">
                    <span>Arsitektur Khusus Industri Apparel & Garmen</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Dirancang Khusus untuk Persediaan Pakaian Jadi (Apparel)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-2">
                    Mengatasi tantangan variasi ukuran (size), batch konveksi, konversi Lusin/Pcs, hingga pencegahan minus mutasi persediaan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Feature 1 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center justify-center font-bold text-lg mb-4">
                        📊
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Inferensi 3-Tier RBL</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Evaluasi otomatis kuadran buffer persediaan (Kritis, Normal, Berlebih) tanpa perlu rumus manual dari staf gudang.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-700 flex items-center justify-center font-bold text-lg mb-4">
                        🏷️
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Pelacakan Lot & Batch</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pencatatan nomor batch konveksi, barcode hangtag, dan surat jalan saat penerimaan barang jadi dari vendor.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center font-bold text-lg mb-4">
                        🛡️
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Validasi Anti-Minus</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Sistem memblokir pengeluaran stok jika kuantitas order melebihi stok aktual fisik di gudang, menjamin integritas data 100%.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center font-bold text-lg mb-4">
                        ⚡
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Generator Draft PO Cepat</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Kalkulasi kuantitas pengadaan optimal (*Max - Aktual*) lengkap dengan estimasi nominal pengadaan untuk divisi Purchasing.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- Multi-Role Showcase Section -->
    <section id="peran" class="py-20 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-14">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold mb-3">
                    <span>Otorisasi & Keamanan Multi-Role</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Ruang Kerja Disesuaikan Berdasarkan Peran
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-2">
                    Setiap staf memiliki akses yang terfokus sesuai dengan tugas dan wewenang operasionalnya di PT Sanartex.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Superadmin -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-xs">
                            👑
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Superadmin</h3>
                        <span class="text-[11px] font-semibold text-slate-500 block mb-2">Kontrol Penuh Sistem</span>
                        <ul class="text-xs text-slate-600 space-y-1.5">
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Manajemen Akun & Role</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Konfigurasi Parameter RBL</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Audit Trail & Master Data</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-slate-900 group-hover:text-teal-600 transition-colors">
                        <span>Masuk Portal</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Kepala Gudang -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-xs">
                            👔
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Kepala Gudang</h3>
                        <span class="text-[11px] font-semibold text-slate-500 block mb-2">Supervisi & Pengawasan</span>
                        <ul class="text-xs text-slate-600 space-y-1.5">
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Monitoring Buffer 3 Zona</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Approval Mutasi Stok</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Laporan Rekapitulasi Persediaan</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-slate-900 group-hover:text-teal-600 transition-colors">
                        <span>Masuk Portal</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Admin Gudang -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-xs">
                            📦
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Admin Gudang</h3>
                        <span class="text-[11px] font-semibold text-slate-500 block mb-2">Operasional Transaksi</span>
                        <ul class="text-xs text-slate-600 space-y-1.5">
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Input Stok Masuk Barang Jadi</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Input Stok Keluar Pesanan Outlet</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Pencetakan Bukti Transaksi</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-slate-900 group-hover:text-teal-600 transition-colors">
                        <span>Masuk Portal</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Purchasing -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-xs">
                            🛒
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Purchasing</h3>
                        <span class="text-[11px] font-semibold text-slate-500 block mb-2">Pengadaan & PO Restock Konveksi</span>
                        <ul class="text-xs text-slate-600 space-y-1.5">
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Analisis SKU Zona Kritis</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Generate Draft Purchase Order</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Estimasi Biaya & Lead Time Vendor</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-slate-900 group-hover:text-teal-600 transition-colors">
                        <span>Masuk Portal</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

            </div>

        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="py-16 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-10 pointer-events-none"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                Siap Mengoptimalkan Persediaan Gudang Pakaian Jadi?
            </h2>
            <p class="mt-3 text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed">
                Akses dashboard terpadu PT Sanartex dan nikmati otomasi kontrol buffer stok dengan tingkat akurasi tinggi.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-extrabold text-xs sm:text-sm shadow-md transition-all">
                    Masuk ke Sistem Sekarang &rarr;
                </a>
                <a href="{{ route('panduan.index') }}" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs sm:text-sm border border-slate-700 transition-all">
                    Pelajari Panduan RBL & SOP
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-8 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/sanartex_horizontal.png?v=4') }}" alt="Logo Sanartex" class="h-8 w-auto object-contain">
                <div>
                    <strong class="text-slate-800 font-bold">PT SANARTEX INDONESIA</strong>
                    <span class="text-[11px] text-slate-400 block">&copy; {{ date('Y') }} &bull; Enterprise Apparel Inventory RBL System</span>
                </div>
            </div>

            <div class="flex items-center gap-6 font-medium">
                <a href="{{ route('login') }}" class="hover:text-slate-900 transition-colors">Portal Login</a>
                <a href="{{ route('panduan.index') }}" class="hover:text-slate-900 transition-colors">Panduan SOP</a>
                <a href="#rbl-simulator" class="hover:text-slate-900 transition-colors">Simulasi Buffer</a>
            </div>
        </div>
    </footer>

</body>
</html>
