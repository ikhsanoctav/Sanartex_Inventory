@php
    $user = Auth::user();
    $notifData = \App\Services\NotificationService::getNotifications();
@endphp

<header class="sticky top-0 z-30 flex items-center justify-between h-20 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200 shadow-xs">
    
    <!-- Left Section: Mobile Menu Toggle & Search -->
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Toggle Button -->
        <button @click="sidebarOpen = !sidebarOpen" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 lg:hidden transition-colors" title="Menu Mobile">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>

        <!-- Desktop Sidebar Collapse Toggle Button -->
        <button @click="toggleCollapse()" 
                class="hidden lg:flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/80 hover:bg-slate-100 hover:border-slate-300 text-slate-600 hover:text-navy-900 transition-all shadow-2xs group focus:outline-none focus:ring-2 focus:ring-orange-500/20" 
                :title="sidebarCollapsed ? 'Perluas Sidebar (Ctrl+B)' : 'Ciutkan Sidebar (Ctrl+B)'">
            <svg class="w-4 h-4 transition-transform duration-300 text-slate-500 group-hover:text-orange-500" :class="sidebarCollapsed ? 'rotate-180 text-orange-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <span class="text-xs font-semibold text-slate-600 group-hover:text-navy-900 transition-colors" x-text="sidebarCollapsed ? 'Perluas Menu' : 'Ciutkan Menu'"></span>
            <kbd class="text-[9px] px-1.5 py-0.5 rounded bg-white text-slate-400 font-mono border border-slate-200 group-hover:text-slate-600 group-hover:border-slate-300 shadow-3xs">Ctrl+B</kbd>
        </button>

        <!-- Quick Search Bar with Barcode Scanner -->
        <form id="topbar_search_form" action="{{ route('products.index') }}" method="GET" class="relative hidden sm:block w-80 md:w-96">
            <input 
                id="topbar_search_input"
                type="text" 
                name="search" 
                placeholder="Cari SKU, nama produk (hoodie, kaos...), rak..." 
                class="w-full pl-10 pr-10 py-2.5 bg-slate-50/90 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 font-medium focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 focus:bg-white transition-all shadow-2xs">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <button 
                type="button" 
                @click="triggerBarcodeScan({ title: 'Scan Barcode Produk untuk Pencarian', callback: (code) => { const el = document.getElementById('topbar_search_input'); if(el){ el.value = code; document.getElementById('topbar_search_form').submit(); } } })"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode untuk Cari Langsung">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </button>
        </form>
    </div>

    <!-- Right Section: Alerts & Profile -->
    <div class="flex items-center gap-3.5">
        
        <!-- Live Interactive Notification Hub -->
        <div class="relative" x-data="{ 
            notifOpen: false,
            activeTab: 'all',
            notifications: {{ Js::from($notifData['items']) }},
            readIds: JSON.parse(localStorage.getItem('sanartex_read_notifs') || '[]'),
            get unreadCount() {
                return this.notifications.filter(n => !this.readIds.includes(n.id)).length;
            },
            get filteredList() {
                if (this.activeTab === 'rbl') return this.notifications.filter(n => n.category === 'rbl');
                if (this.activeTab === 'activity') return this.notifications.filter(n => n.category === 'activity');
                return this.notifications;
            },
            isRead(id) {
                return this.readIds.includes(id);
            },
            markAllAsRead() {
                this.readIds = this.notifications.map(n => n.id);
                localStorage.setItem('sanartex_read_notifs', JSON.stringify(this.readIds));
            },
            markItemAsRead(id) {
                if (!this.readIds.includes(id)) {
                    this.readIds.push(id);
                    localStorage.setItem('sanartex_read_notifs', JSON.stringify(this.readIds));
                }
            },
            fetchLatestNotifs() {
                fetch('{{ route('notifications.feed') }}')
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.items) {
                            this.notifications = data.items;
                        }
                    })
                    .catch(e => console.debug('Notif sync error', e));
            },
            init() {
                // Poll feed every 30s
                setInterval(() => this.fetchLatestNotifs(), 30000);
            }
        }">
            <!-- Bell Trigger Button -->
            <button 
                @click="notifOpen = !notifOpen"
                class="relative p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-navy-900 transition-colors shadow-2xs group focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                title="Pusat Notifikasi">
                <svg class="w-5 h-5 text-slate-600 group-hover:text-navy-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                <!-- Unread Counter Pill Badge -->
                <template x-if="unreadCount > 0">
                    <span 
                        x-text="unreadCount > 9 ? '9+' : unreadCount"
                        class="absolute -top-1 -right-1 min-w-[19px] h-[19px] px-1 rounded-full bg-orange-500 text-white font-bold text-[10px] flex items-center justify-center shadow-xs border-2 border-white animate-pulse">
                    </span>
                </template>
            </button>

            <!-- Dropdown Notification Panel -->
            <div 
                x-show="notifOpen" 
                @click.away="notifOpen = false"
                x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                class="absolute right-0 mt-2.5 w-84 sm:w-96 rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden z-50 text-xs">
                
                <!-- Panel Header -->
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-navy-900 text-xs">Notifikasi & Peringatan</span>
                        <template x-if="unreadCount > 0">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-500 text-white" x-text="unreadCount + ' Baru'"></span>
                        </template>
                    </div>

                    <template x-if="unreadCount > 0">
                        <button 
                            @click="markAllAsRead()" 
                            class="text-[11px] font-medium text-orange-600 hover:text-orange-700 flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Tandai Dibaca</span>
                        </button>
                    </template>
                </div>

                <!-- Tab Category Filter -->
                <div class="px-3 pt-2.5 pb-2 bg-white border-b border-slate-100 flex items-center gap-1.5 text-xs">
                    <button 
                        @click="activeTab = 'all'"
                        :class="activeTab === 'all' ? 'bg-navy-900 text-white font-semibold shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                        class="px-2.5 py-1 rounded-lg text-[11px] transition-colors flex items-center gap-1">
                        <span>Semua</span>
                        <span class="text-[9px] px-1 py-0.2 rounded-full" :class="activeTab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" x-text="notifications.length"></span>
                    </button>
                    <button 
                        @click="activeTab = 'rbl'"
                        :class="activeTab === 'rbl' ? 'bg-navy-900 text-white font-semibold shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                        class="px-2.5 py-1 rounded-lg text-[11px] transition-colors flex items-center gap-1">
                        <span>Peringatan Stok</span>
                        <span class="text-[9px] px-1 py-0.2 rounded-full" :class="activeTab === 'rbl' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" x-text="notifications.filter(n => n.category === 'rbl').length"></span>
                    </button>
                    <button 
                        @click="activeTab = 'activity'"
                        :class="activeTab === 'activity' ? 'bg-navy-900 text-white font-semibold shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                        class="px-2.5 py-1 rounded-lg text-[11px] transition-colors flex items-center gap-1">
                        <span>Aktivitas</span>
                        <span class="text-[9px] px-1 py-0.2 rounded-full" :class="activeTab === 'activity' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" x-text="notifications.filter(n => n.category === 'activity').length"></span>
                    </button>
                </div>

                <!-- Notifications List Container -->
                <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                    <template x-for="item in filteredList" :key="item.id">
                        <div 
                            @click="markItemAsRead(item.id)"
                            :class="isRead(item.id) ? 'bg-white opacity-80' : 'bg-orange-50/25'"
                            class="p-3.5 hover:bg-slate-50/90 transition-colors flex items-start gap-3 group">
                            
                            <!-- Icon Indicator -->
                            <div :class="item.icon_bg" class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5 shadow-2xs">
                                <template x-if="item.severity === 'critical'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </template>
                                <template x-if="item.severity === 'warning'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </template>
                                <template x-if="item.severity === 'info'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </template>
                                <template x-if="item.severity === 'success'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </template>
                                <template x-if="item.severity === 'primary'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </template>
                            </div>

                            <!-- Content Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                    <h4 class="font-bold text-slate-900 truncate text-[11px]" x-text="item.title"></h4>
                                    <span :class="item.badge_class" class="text-[9px] font-bold px-1.5 py-0.2 rounded-md uppercase" x-text="item.badge"></span>
                                </div>
                                <p class="text-[11px] text-slate-600 leading-snug line-clamp-2" x-text="item.message"></p>
                                <div class="flex items-center justify-between mt-1.5 pt-1">
                                    <span class="text-[10px] text-slate-400 font-medium" x-text="item.time"></span>
                                    <a 
                                        :href="item.url" 
                                        class="text-[10px] font-bold text-orange-600 hover:text-orange-700 flex items-center gap-0.5">
                                        <span x-text="item.action_label"></span>
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template x-if="filteredList.length === 0">
                        <div class="px-4 py-8 text-center text-slate-400">
                            <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <p class="font-medium text-xs text-slate-600">Tidak ada notifikasi aktif</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Semua parameter buffer stok dan transaksi dalam keadaan aman.</p>
                        </div>
                    </template>
                </div>

                <!-- Panel Footer Quick Shortcuts -->
                <div class="p-2.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs">
                    <a href="{{ route('rbl.analisis') }}" class="text-[11px] font-bold text-navy-900 hover:text-orange-600 transition-colors">
                        Analisis RBL &rarr;
                    </a>
                    <a href="{{ route('laporan.index') }}" class="text-[11px] font-medium text-slate-500 hover:text-slate-900 transition-colors">
                        Laporan Mutasi &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Role Badge Display -->
        <div class="hidden md:flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/80 text-xs font-medium text-slate-700 shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Peran: <strong class="text-navy-900 font-bold">{{ $user->role_label }}</strong></span>
        </div>

        <!-- Profile Menu -->
        <div class="relative" x-data="{ profileOpen: false }">
            <button 
                @click="profileOpen = !profileOpen"
                class="flex items-center gap-2.5 p-1.5 rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all shadow-2xs">
                <div class="w-9 h-9 rounded-lg bg-navy-900 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="hidden sm:block text-left pr-1.5">
                    <span class="block text-xs font-bold text-navy-900 truncate max-w-[130px]">{{ $user->name ?? 'User' }}</span>
                </div>
            </button>

            <div 
                x-show="profileOpen" 
                @click.away="profileOpen = false"
                x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                class="absolute right-0 mt-2 w-52 rounded-2xl bg-white border border-slate-200 shadow-xl p-1.5 z-50 text-xs">
                <div class="px-3 py-2.5 border-b border-slate-100 mb-1">
                    <p class="font-bold text-slate-900 truncate">{{ $user->name ?? 'User' }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ $user->email ?? '' }}</p>
                    <span class="inline-block mt-1 text-[10px] font-semibold px-2 py-0.2 rounded-md {{ $user->role_badge_class }}">
                        {{ $user->role_label }}
                    </span>
                </div>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 text-slate-700 font-medium transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-orange-50 hover:text-orange-700 text-slate-700 font-medium transition-colors">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Pengaturan Profil</span>
                </a>
                <a href="{{ route('panduan.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 text-slate-700 font-medium transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Panduan SOP</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="pt-1 mt-1 border-t border-slate-100">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-rose-50 text-rose-600 font-semibold transition-colors">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </div>

    </div>

</header>
