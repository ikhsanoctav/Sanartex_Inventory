<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SANARTEX Textile Inventory</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#f0f4f9',
                            100: '#dde7f2',
                            200: '#c0d3e7',
                            300: '#94b7d7',
                            400: '#6195c3',
                            500: '#3f7bb2',
                            600: '#2f6296',
                            700: '#274f7b',
                            800: '#234367',
                            900: '#0f243f', /* Biru Navy Gelap (dari Logo) */
                            950: '#0a1728',
                        },
                        candescent: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316', /* Oranye Terang / Candescent Orange */
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-slate-50 text-slate-800 flex font-sans overflow-hidden" x-data="{ sidebarOpen: false }">

    <div class="flex-1 flex overflow-hidden h-screen w-full">
        
        <!-- Modular Sidebar -->
        @include('layouts.sidebar')

        <!-- Mobile Backdrop -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false" 
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/40 z-30 lg:hidden">
        </div>

        <!-- Main Content Area (Full Width Spanning to Right Edge) -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto custom-scrollbar">
            
            <!-- Modular Topbar -->
            @include('layouts.topbar')

            <!-- Global Notifications -->
            <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                         class="mb-4 flex items-center justify-between p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" 
                         class="mb-4 flex items-center justify-between p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium shadow-sm">
                        <p class="font-semibold mb-1">Periksa kembali data Anda:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Page Content (Full Width Edge-to-Edge with Standard Padding) -->
            <main class="flex-1 w-full px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </main>

            <!-- Minimal Footer -->
            <footer class="mt-auto border-t border-slate-200 bg-white py-3.5 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                    <strong class="text-slate-700">PT SANARTEX</strong> &copy; {{ date('Y') }} &bull; Sistem Manajemen Inventori Tekstil Berbasis RBL
                </div>
                <div class="flex items-center gap-4 text-slate-500">
                    <a href="{{ route('panduan.index') }}" class="hover:text-slate-900 transition-colors">Panduan SOP</a>
                </div>
            </footer>

        </div>

    </div>

    <!-- Global Barcode & QR Scanner Modal Component -->
    @include('layouts.scanner')

    <!-- Universal Realtime AJAX Table & Filter Engine -->
    @include('layouts.ajax-filter')

    @stack('scripts')
</body>
</html>
