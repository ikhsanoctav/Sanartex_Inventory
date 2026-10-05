<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 text-slate-800 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - PT SANARTEX Textile Inventory</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">

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
                        navy: {
                            800: '#142c4b',
                            900: '#0f243f',
                            950: '#0a182b',
                        },
                        candescent: {
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-slate-50 text-slate-800 flex items-center justify-center p-4 sm:p-6 lg:p-10 selection:bg-orange-500 selection:text-white"
      x-data="{
          email: '{{ old('email', '') }}',
          password: '',
          showPassword: false
      }">

    <!-- Main Container (Dual Pane Split Card: Navy Left Panel + Clean White Right Form) -->
    <div class="w-full max-w-5xl rounded-3xl bg-white border border-slate-200/90 shadow-2xl shadow-slate-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[550px]">
        
        <!-- LEFT SHOWCASE PANEL (Warna Utama: Biru Navy Gelap + Aksen Oranye Terang) -->
        <div class="hidden lg:flex lg:col-span-5 flex-col justify-between p-10 bg-[#0f243f] text-white">
            
            <!-- Top Header -->
            <div class="space-y-3">
                <h2 class="text-2xl font-extrabold text-white tracking-tight leading-snug">
                    Kendali Cerdas Stok Kain & Benang Tekstil
                </h2>
                <!-- Teks Non-Prioritas: Abu-abu Menengah -->
                <p class="text-xs text-slate-400 leading-relaxed">
                    Sistem inventori otomatisasi kontrol persediaan bahan baku kain dan benang tekstil untuk UMKM Konveksi Sanartex.
                </p>
            </div>

            <!-- Sleek Highlights (Aksen Oranye Terang) -->
            <div class="space-y-4 py-6">
                <div class="flex items-start gap-3.5 text-xs text-slate-200">
                    <div class="w-7 h-7 rounded-xl bg-orange-500/15 border border-orange-500/30 text-orange-400 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <strong class="text-white block font-bold text-xs">Pencatatan Mutasi Instan</strong>
                        <span class="text-slate-400 text-[11px] leading-relaxed block mt-0.5">Stok masuk & keluar tercatat rapi dan real-time.</span>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 text-xs text-slate-200">
                    <div class="w-7 h-7 rounded-xl bg-orange-500/15 border border-orange-500/30 text-orange-400 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <strong class="text-white block font-bold text-xs">Evaluasi Buffer Stok</strong>
                        <span class="text-slate-400 text-[11px] leading-relaxed block mt-0.5">Deteksi dini persediaan aman, kritis, dan berlebih.</span>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 text-xs text-slate-200">
                    <div class="w-7 h-7 rounded-xl bg-orange-500/15 border border-orange-500/30 text-orange-400 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <strong class="text-white block font-bold text-xs">Akses Terproteksi & Traceable</strong>
                        <span class="text-slate-400 text-[11px] leading-relaxed block mt-0.5">Sistem otentikasi aman untuk pemilik & tim gudang.</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Note (Abu-abu Menengah) -->
            <div class="pt-4 border-t border-slate-800 text-[11px] text-slate-400 font-medium">
                <span>&copy; {{ date('Y') }} PT Sanartex Indonesia</span>
            </div>

        </div>

        <!-- RIGHT LOGIN FORM PANEL (Warna Latar: Putih) -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-10 lg:p-12 flex flex-col justify-center text-slate-800">
            
            <div>
                <!-- Brand Logo (High-Res Horizontal Lockup) -->
                <div class="flex items-center mb-8">
                    <img src="{{ asset('img/sanartex_horizontal.png') }}?v=3" alt="Logo Sanartex" class="h-12 sm:h-13 w-auto object-contain">
                </div>

                <!-- Heading (Biru Navy Gelap) -->
                <div class="mb-6">
                    <h1 class="text-2xl font-extrabold text-[#0f243f] tracking-tight">Selamat Datang Kembali</h1>
                    <!-- Teks Non-Prioritas: Abu-abu Menengah -->
                    <p class="text-xs text-slate-500 mt-1">Masukkan email dan kata sandi Anda untuk mengakses dashboard persediaan.</p>
                </div>

                <!-- Flash Alert Message -->
                @if($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-5 p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-medium flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                x-model="email"
                                required 
                                autofocus
                                placeholder="contoh: nama@sanartex.com" 
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/90 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 font-medium focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                        </div>
                    </div>

                    <!-- Password Input with Show/Hide Toggle -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700">Kata Sandi</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                x-model="password"
                                required 
                                placeholder="Masukkan kata sandi..." 
                                class="w-full pl-10 pr-10 py-2.5 bg-slate-50/90 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 font-medium focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                            
                            <!-- Toggle Button -->
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-orange-600 transition-colors">
                                <template x-if="!showPassword">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </template>
                                <template x-if="showPassword">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </template>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me (Aksen Oranye) -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer font-medium select-none">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                            <span>Ingat sesi masuk saya</span>
                        </label>
                    </div>

                    <!-- Submit Button (Warna Aksen 1: Oranye Terang / Candescent Orange) -->
                    <button 
                        type="submit" 
                        class="w-full mt-2 py-3 px-4 rounded-xl bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-bold text-xs tracking-wide transition-all shadow-md shadow-orange-500/20 hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

            </div>

        </div>

    </div>

</body>
</html>
