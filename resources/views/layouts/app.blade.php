<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: true, darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Supply Chain') - IKM Marmer Tulungagung</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        marble: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #14b8a6; border-radius: 3px; }
        .glass { 
            background: rgba(255,255,255,0.7); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .dark .glass { 
            background: rgba(30,41,59,0.8); 
            border: 1px solid rgba(255,255,255,0.1);
        }
        .gradient-text {
            background: linear-gradient(135deg, #14b8a6, #0d9488, #0f766e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(20, 184, 166, 0.25);
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(20, 184, 166, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(20, 184, 166, 0); }
        }
        .pulse-glow { animation: pulse-glow 2s infinite; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-slate-900 min-h-screen text-gray-800 dark:text-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside 
            class="sidebar-transition fixed inset-y-0 left-0 z-50 glass dark:glass shadow-xl"
            :class="sidebarOpen ? 'w-72' : 'w-20'"
        >
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between p-5 border-b border-gray-200/50 dark:border-slate-700/50">
                    <a href="{{ route('landing') }}" class="flex items-center gap-3" x-show="sidebarOpen" x-transition>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-marble-400 to-marble-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-gem text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold gradient-text">MarmerSC</h1>
                            <p class="text-xs text-gray-500 dark:text-gray-400">E-Supply Chain</p>
                        </div>
                    </a>
                    <div x-show="!sidebarOpen" class="w-full flex justify-center" x-transition>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-marble-400 to-marble-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-gem text-white text-lg"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Sesuai Role Pengguna -->
                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    @php
                        $user = Auth::user();
                        $role = $user?->role ?? 'buyer';

                        if ($role === 'buyer') {
                            $navItems = [
                                ['route' => 'catalog', 'icon' => 'fas fa-store', 'label' => 'Katalog Produk Marmer', 'match' => 'catalog*'],
                                ['route' => 'pesanan.public_create', 'icon' => 'fas fa-hand-holding-heart', 'label' => 'Pesan Custom Marmer', 'match' => 'pesan-kustom*'],
                                ['route' => 'tracking.index', 'icon' => 'fas fa-magnifying-glass-location', 'label' => 'Lacak Pesanan (WIP)', 'match' => 'lacak-pesanan*'],
                                ['route' => 'buyer.profile', 'icon' => 'fas fa-user-circle', 'label' => 'Profil & Pesanan Saya', 'match' => 'profil-saya*'],
                            ];
                        } elseif ($role === 'operator') {
                            $navItems = [
                                ['route' => 'operator.index', 'icon' => 'fas fa-hammer', 'label' => 'Shopfloor Operator', 'match' => 'operator*'],
                                ['route' => 'pesanan.index', 'icon' => 'fas fa-clipboard-list', 'label' => 'Daftar Pesanan & SPK', 'match' => 'pesanan*'],
                                ['route' => 'bahan-baku.index', 'icon' => 'fas fa-cubes', 'label' => 'Stok Balok Bahan Baku', 'match' => 'bahan-baku*'],
                                ['route' => 'alur-proses.index', 'icon' => 'fas fa-project-diagram', 'label' => 'Alur Proses & SOP VSM', 'match' => 'alur-proses*'],
                                ['route' => 'limbah.index', 'icon' => 'fas fa-recycle', 'label' => 'Catat Limbah Bengkel', 'match' => 'limbah*'],
                                ['route' => 'produksi.index', 'icon' => 'fas fa-industry', 'label' => 'Rekap Output Produksi', 'match' => 'produksi*'],
                            ];
                        } else {
                            // Admin (Akses Penuh Seluruh Modul)
                            $navItems = [
                                ['route' => 'dashboard', 'icon' => 'fas fa-chart-pie', 'label' => 'Executive Dashboard', 'match' => 'dashboard'],
                                ['route' => 'laporan.index', 'icon' => 'fas fa-file-invoice-dollar', 'label' => 'Laporan & Ekspor Data', 'match' => 'laporan*'],
                                ['route' => 'users.index', 'icon' => 'fas fa-users-gear', 'label' => 'Kelola Akun & Karyawan', 'match' => 'users*'],
                                ['route' => 'pesanan.index', 'icon' => 'fas fa-clipboard-list', 'label' => 'Pesanan & WIP Tracking', 'match' => 'pesanan*'],
                                ['route' => 'operator.index', 'icon' => 'fas fa-hammer', 'label' => 'Shopfloor Operator', 'match' => 'operator*'],
                                ['route' => 'bahan-baku.index', 'icon' => 'fas fa-cubes', 'label' => 'Inventaris Bahan Baku', 'match' => 'bahan-baku*'],
                                ['route' => 'produk.index', 'icon' => 'fas fa-box-open', 'label' => 'Master Produk', 'match' => 'produk*'],
                                ['route' => 'catalog', 'icon' => 'fas fa-store', 'label' => 'Katalog Publik', 'match' => 'catalog*'],
                                ['route' => 'produksi.index', 'icon' => 'fas fa-industry', 'label' => 'Rekap Produksi', 'match' => 'produksi*'],
                                ['route' => 'alur-proses.index', 'icon' => 'fas fa-project-diagram', 'label' => 'Alur Proses & VSM', 'match' => 'alur-proses*'],
                                ['route' => 'limbah.index', 'icon' => 'fas fa-recycle', 'label' => 'Manajemen Limbah', 'match' => 'limbah*'],
                                ['route' => 'pemanfaatan-limbah.index', 'icon' => 'fas fa-leaf', 'label' => 'Pemanfaatan Limbah', 'match' => 'pemanfaatan-limbah*'],
                                ['route' => 'kebutuhan-sistem.index', 'icon' => 'fas fa-cogs', 'label' => 'Kebutuhan Sistem', 'match' => 'kebutuhan-sistem*'],
                                ['route' => 'ikm.index', 'icon' => 'fas fa-building', 'label' => 'Profil Sentra IKM', 'match' => 'ikm*'],
                            ];
                        }
                    @endphp
                    
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs($item['match']) 
                                     ? 'bg-gradient-to-r from-marble-500 to-marble-600 text-white shadow-lg shadow-marble-500/30' 
                                     : 'text-gray-600 dark:text-gray-300 hover:bg-marble-50 dark:hover:bg-slate-800 hover:text-marble-600 dark:hover:text-marble-400' }}"
                           :class="!sidebarOpen && 'justify-center'"
                        >
                            <i class="{{ $item['icon'] }} text-base w-5 text-center"></i>
                            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                
                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-gray-200/50 dark:border-slate-700/50">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                        <span x-show="sidebarOpen" x-transition>Tutup Sidebar</span>
                    </button>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 sidebar-transition" :class="sidebarOpen ? 'ml-72' : 'ml-20'">
            <!-- Top Bar -->
            <header class="sticky top-0 z-40 glass dark:glass border-b border-gray-200/50 dark:border-slate-700/50">
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800">
                            <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
                        </button>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">@yield('page-subtitle', 'E-Supply Chain IKM Marmer Tulungagung')</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                                class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                            <i class="fas" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-gray-600'"></i>
                        </button>

                        <!-- Notifikasi Interaktif (Poin 14) -->
                        <div class="relative" x-data="{ 
                            open: false, 
                            unreadCount: 0, 
                            notifications: [],
                            fetchNotif() {
                                fetch('{{ route('notifications.index') }}')
                                    .then(res => res.json())
                                    .then(data => {
                                        this.unreadCount = data.unread_count;
                                        this.notifications = data.notifications;
                                    });
                            },
                            markRead(id) {
                                fetch(`/api/notifications/${id}/read`, {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                                }).then(() => this.fetchNotif());
                            },
                            markAll() {
                                fetch('{{ route('notifications.markAllRead') }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                                }).then(() => this.fetchNotif());
                            }
                        }" x-init="fetchNotif()" @click.outside="open = false">
                            <button @click="open = !open" class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors relative" title="Notifikasi Sistem">
                                <i class="fas fa-bell text-gray-600 dark:text-gray-300"></i>
                                <template x-if="unreadCount > 0">
                                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full pulse-glow"></span>
                                </template>
                            </button>

                            <!-- Dropdown Notifikasi -->
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-2xl py-3 z-50 text-xs">
                                <div class="px-4 pb-2 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 font-bold text-gray-800 dark:text-white">
                                        <i class="fas fa-bell text-teal-500"></i>
                                        <span>Notifikasi Aktivitas</span>
                                        <template x-if="unreadCount > 0">
                                            <span class="px-1.5 py-0.5 rounded-full bg-rose-500 text-white text-[10px]" x-text="unreadCount"></span>
                                        </template>
                                    </div>
                                    <template x-if="unreadCount > 0">
                                        <button @click="markAll()" class="text-[10px] text-teal-600 dark:text-teal-400 font-semibold hover:underline">
                                            Tandai Semua Dibaca
                                        </button>
                                    </template>
                                </div>

                                <div class="max-h-72 overflow-y-auto divide-y divide-gray-100 dark:divide-slate-700/60">
                                    <template x-for="item in notifications" :key="item.id">
                                        <div class="p-3 hover:bg-gray-50 dark:hover:bg-slate-700/40 transition-colors flex items-start gap-2.5" :class="!item.is_read ? 'bg-teal-50/40 dark:bg-teal-950/20' : ''">
                                            <div class="w-2 h-2 rounded-full mt-1.5 shrink-0" :class="!item.is_read ? 'bg-teal-500' : 'bg-gray-300 dark:bg-slate-600'"></div>
                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-900 dark:text-white text-xs" x-text="item.judul"></h4>
                                                <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-0.5" x-text="item.pesan"></p>
                                                <div class="flex items-center justify-between mt-2">
                                                    <a :href="item.link || '#'" @click="markRead(item.id)" class="text-[10px] text-teal-600 dark:text-teal-400 font-bold hover:underline flex items-center gap-1">
                                                        <span>Lihat Rincian</span> <i class="fas fa-arrow-right text-[8px]"></i>
                                                    </a>
                                                    <template x-if="!item.is_read">
                                                        <button @click="markRead(item.id)" class="text-[10px] text-gray-400 hover:text-gray-600">
                                                            Tandai dibaca
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="notifications.length === 0">
                                        <div class="p-6 text-center text-gray-400 text-xs">
                                            <i class="fas fa-check-double text-2xl mb-1 text-gray-300"></i>
                                            <p>Belum ada notifikasi aktivitas baru.</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile & Dropdown -->
                        @auth
                            <div class="relative pl-3 border-l border-gray-200 dark:border-slate-700" x-data="{ userMenu: false }" @click.outside="userMenu = false">
                                <button @click="userMenu = !userMenu" class="flex items-center gap-2 group p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border border-teal-500">
                                    <div class="text-left hidden sm:block">
                                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 block leading-tight">{{ Auth::user()->name }}</span>
                                        <span class="text-[10px] text-teal-600 dark:text-teal-400 block font-bold capitalize">{{ Auth::user()->role_label }}</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-[10px] text-gray-400 ml-0.5"></i>
                                </button>

                                <div x-show="userMenu" x-transition class="absolute right-0 mt-2 w-52 rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-xl py-2 z-50 text-xs">
                                    <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-700">
                                        <p class="font-bold text-gray-800 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    
                                    @if(Auth::user()->isBuyer())
                                        <a href="{{ route('buyer.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700">
                                            <i class="fas fa-id-card text-teal-500 w-4"></i> Profil & Alamat Saya
                                        </a>
                                    @endif

                                    <a href="{{ route('landing') }}" class="flex items-center gap-2.5 px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700">
                                        <i class="fas fa-house text-teal-500 w-4"></i> Halaman Depan
                                    </a>

                                    <div class="border-t border-gray-100 dark:border-slate-700 my-1"></div>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full flex items-center gap-2.5 px-4 py-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/20 font-semibold text-left cursor-pointer">
                                            <i class="fas fa-right-from-bracket w-4"></i> Keluar (Logout)
                                        </a>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2 pl-3 border-l border-gray-200 dark:border-slate-700">
                                <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                                    <i class="fas fa-arrow-right-to-bracket text-xs"></i> Masuk
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition class="mx-6 mt-4">
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span class="text-sm text-emerald-700 dark:text-emerald-300">{{ session('success') }}</span>
                        <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition class="mx-6 mt-4">
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800">
                        <i class="fas fa-exclamation-circle text-rose-500"></i>
                        <span class="text-sm text-rose-700 dark:text-rose-300">{{ session('error') }}</span>
                        <button @click="show = false" class="ml-auto text-rose-400 hover:text-rose-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            <!-- Page Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
