<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Supply Chain IKM Marmer & Batu Alam Tulungagung</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        },
                        marble: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .hero-pattern {
            background-color: #0b1329;
            background-image: radial-gradient(at 0% 0%, rgba(20, 184, 166, 0.3) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.25) 0px, transparent 50%),
                              radial-gradient(at 50% 50%, rgba(15, 118, 110, 0.2) 0px, transparent 50%);
        }
        .glow-effect {
            box-shadow: 0 0 50px -10px rgba(20, 184, 166, 0.5);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased selection:bg-teal-500 selection:text-white">

    <!-- Top Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b border-slate-800/80 bg-slate-950/85 backdrop-blur-xl" x-data="{ mobileNav: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center shadow-lg shadow-teal-500/30 ring-2 ring-teal-400/20 shrink-0">
                    <i class="fas fa-cubes-stacked text-white text-base sm:text-lg"></i>
                </div>
                <div>
                    <span class="text-lg sm:text-xl font-extrabold tracking-tight bg-gradient-to-r from-teal-300 via-emerald-200 to-white bg-clip-text text-transparent">MarmerSC</span>
                    <span class="block text-[9px] sm:text-[10px] font-semibold tracking-wider uppercase text-teal-400/90">E-Supply Chain Tulungagung</span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-slate-300">
                <a href="#fitur" class="hover:text-teal-300 transition-colors">4 Pilar Supply Chain</a>
                <a href="#peran" class="hover:text-teal-300 transition-colors">3 Peran Sistem</a>
                <a href="#ikm" class="hover:text-teal-300 transition-colors">Mitra IKM</a>
                <a href="{{ route('catalog') }}" class="hover:text-teal-300 transition-colors flex items-center gap-1.5 text-teal-300 font-semibold">
                    <i class="fas fa-store text-xs text-teal-400"></i> Katalog Produk
                </a>
                <a href="{{ route('tracking.index') }}" class="hover:text-teal-300 transition-colors flex items-center gap-1.5 text-cyan-300">
                    <i class="fas fa-search text-xs"></i> Lacak Pesanan
                </a>
                <a href="https://wa.me/6281234567890?text=Halo%20Perajin%20Marmer%20Tulungagung,%20saya%20ingin%20konsultasi%20pemesanan%20marmer." target="_blank" class="text-emerald-400 hover:text-emerald-300 flex items-center gap-1.5" title="Konsultasi langsung tanpa login">
                    <i class="fab fa-whatsapp"></i> Tanya WA
                </a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                @guest
                    <a href="{{ route('login') }}" class="px-3 sm:px-4 py-2 rounded-xl text-xs font-bold text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500 transition-all flex items-center gap-1.5">
                        <i class="fas fa-right-to-bracket text-teal-400"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 text-xs font-extrabold shadow-md shadow-teal-500/20 transition-all">
                        Daftar
                    </a>
                @else
                    <div class="flex items-center gap-2 sm:gap-3">
                        <span class="text-xs text-slate-300 hidden md:inline">Halo, <strong>{{ Auth::user()->name }}</strong></span>
                        <a href="{{ Auth::user()->role === 'operator' ? route('operator.index') : (Auth::user()->role === 'buyer' ? route('buyer.profile') : route('dashboard')) }}"
                           class="px-3 sm:px-4 py-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 text-white text-xs font-bold shadow-md shadow-teal-500/25 flex items-center gap-1.5">
                            <i class="fas fa-gauge"></i>
                            <span>{{ Auth::user()->role === 'buyer' ? 'Pesanan Saya' : 'Portal' }}</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="p-2 rounded-xl text-slate-400 hover:text-rose-400 text-xs border border-slate-800 cursor-pointer inline-flex items-center" title="Keluar">
                                <i class="fas fa-power-off"></i>
                            </a>
                        </form>
                    </div>
                @endguest

                <!-- Mobile Hamburger Toggle -->
                <button @click="mobileNav = !mobileNav" class="lg:hidden p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white focus:outline-none">
                    <i class="fas" :class="mobileNav ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu (Responsive HP, iPad, Tablet) -->
        <div x-show="mobileNav" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.outside="mobileNav = false"
             class="lg:hidden bg-slate-900/95 border-b border-slate-800 backdrop-blur-2xl px-6 py-5 space-y-3"
             style="display: none;">
            <a href="#fitur" @click="mobileNav = false" class="block py-2 text-sm text-slate-300 hover:text-teal-400 font-medium">
                <i class="fas fa-cubes text-teal-400 mr-2 w-4"></i> 4 Pilar Supply Chain
            </a>
            <a href="#peran" @click="mobileNav = false" class="block py-2 text-sm text-slate-300 hover:text-teal-400 font-medium">
                <i class="fas fa-users-gear text-teal-400 mr-2 w-4"></i> 3 Peran Sistem
            </a>
            <a href="#ikm" @click="mobileNav = false" class="block py-2 text-sm text-slate-300 hover:text-teal-400 font-medium">
                <i class="fas fa-building text-teal-400 mr-2 w-4"></i> Sentra Mitra IKM
            </a>
            <a href="{{ route('catalog') }}" class="block py-2 text-sm text-teal-300 hover:text-teal-200 font-bold border-t border-slate-800 pt-3">
                <i class="fas fa-store text-teal-400 mr-2 w-4"></i> Katalog Produk Digital
            </a>
            <a href="{{ route('tracking.index') }}" class="block py-2 text-sm text-cyan-300 hover:text-cyan-200 font-bold">
                <i class="fas fa-search text-cyan-400 mr-2 w-4"></i> Lacak Pesanan (WIP)
            </a>
            <a href="https://wa.me/6281234567890?text=Halo%20Perajin%20Marmer%20Tulungagung,%20saya%20ingin%20konsultasi%20pemesanan%20marmer." target="_blank" class="block py-2 text-sm text-emerald-400 hover:text-emerald-300 font-bold">
                <i class="fab fa-whatsapp mr-2 w-4"></i> Konsultasi WhatsApp
            </a>
            @guest
                <div class="pt-3 border-t border-slate-800 grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}" class="w-full py-2.5 rounded-xl border border-slate-700 text-center text-xs font-bold text-white hover:bg-slate-800">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="w-full py-2.5 rounded-xl bg-teal-500 text-slate-950 text-center text-xs font-black hover:bg-teal-400">
                        Daftar Akun
                    </a>
                </div>
            @endguest
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-36 pb-24 md:pt-48 md:pb-36 hero-pattern overflow-hidden">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-teal-500/30 bg-teal-500/10 text-teal-300 text-xs font-semibold uppercase tracking-wider mb-8 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
                    Digitalisasi Rantai Pasok IKM Marmer Campurdarat
                </div>
                
                <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight text-white leading-[1.1] mb-6">
                    Solusi <span class="bg-gradient-to-r from-teal-400 via-emerald-300 to-cyan-300 bg-clip-text text-transparent">E-Supply Chain</span> Modern Marmer Tulungagung
                </h1>
                
                <p class="text-lg md:text-xl text-slate-300 leading-relaxed font-normal mb-10">
                    Sistem terpadu digitalisasi stok bahan baku bongkahan, pelacakan proses produksi (WIP), katalog interaktif buyer, hingga sirkular ekonomi hilirisasi limbah batu marmer & batu kali.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <!-- Tombol 1: Verifikasi Login ke Dashboard -->
                    @guest
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-sm shadow-xl shadow-teal-500/30 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-lock text-base"></i>
                            <span>Buka Dashboard Operasional</span>
                        </a>
                    @else
                        <a href="{{ Auth::user()->role === 'operator' ? route('operator.index') : (Auth::user()->role === 'buyer' ? route('catalog') : route('dashboard')) }}"
                           class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-sm shadow-xl shadow-teal-500/30 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-chart-line text-base"></i>
                            <span>Buka Dashboard {{ Auth::user()->role_label }}</span>
                        </a>
                    @endguest

                    <!-- Tombol 2: Katalog Produk Khusus Buyer (Publik Tanpa Login) -->
                    <a href="{{ route('catalog') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-2xl border border-slate-700 bg-slate-900/80 hover:bg-slate-800 text-teal-300 font-bold text-sm shadow-lg hover:border-teal-500/40 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-store text-base text-teal-400"></i>
                        <span>Katalog Produk Marmer</span>
                    </a>

                    <!-- Tombol 3: Konsultasi WhatsApp Langsung (Bebas Login) -->
                    <a href="https://wa.me/6281234567890?text=Halo%20Perajin%20Marmer%20Tulungagung,%20saya%20ingin%20tanya%20produk%20dan%20harga." target="_blank" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/40 text-emerald-300 font-bold text-sm transition-all flex items-center justify-center gap-2">
                        <i class="fab fa-whatsapp text-emerald-400 text-lg"></i>
                        <span>Tanya via WhatsApp</span>
                    </a>
                </div>
            </div>

            <!-- Dashboard Mockup Showcase -->
            <div class="mt-20 max-w-5xl mx-auto rounded-3xl border border-slate-800 bg-slate-900/60 p-4 shadow-2xl backdrop-blur-xl relative">
                <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-6 overflow-hidden">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500/80"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500/80"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500/80"></span>
                            <span class="text-slate-400 ml-2 font-mono">marmer-supply-chain.tulungagung.go.id</span>
                        </div>
                        <span class="text-teal-400 font-semibold flex items-center gap-1.5">
                            <i class="fas fa-circle text-[8px] animate-pulse"></i> Live Supply Chain Status
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-xl">
                            <p class="text-xs text-slate-400 font-medium">Bahan Baku Balok</p>
                            <h3 class="text-2xl font-bold text-teal-400 mt-1">40 Batch</h3>
                            <span class="text-[11px] text-emerald-400">Besole & Batu Kali</span>
                        </div>
                        <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-xl">
                            <p class="text-xs text-slate-400 font-medium">Total Produksi 2026</p>
                            <h3 class="text-2xl font-bold text-emerald-400 mt-1">32.610 Pcs</h3>
                            <span class="text-[11px] text-slate-400">Wastafel & Stepping</span>
                        </div>
                        <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-xl">
                            <p class="text-xs text-slate-400 font-medium">Pesanan Aktif (WIP)</p>
                            <h3 class="text-2xl font-bold text-cyan-400 mt-1">6 Pesanan</h3>
                            <span class="text-[11px] text-amber-400 flex items-center gap-1 mt-1"><i class="fas fa-spinner fa-spin"></i> Dalam Pengerjaan</span>
                        </div>
                        <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-xl">
                            <p class="text-xs text-slate-400 font-medium">Hilirisasi Limbah</p>
                            <h3 class="text-2xl font-bold text-teal-300 mt-1">Rp 3,2 Juta</h3>
                            <span class="text-[11px] text-teal-400">Circular Economy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 Peran Pengguna (Multi-Role Architecture) -->
    <section id="peran" class="py-20 bg-slate-950 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-teal-400 text-xs font-bold tracking-widest uppercase">Multi-Role Architecture</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Bukan Hanya untuk Admin</h2>
                <p class="text-slate-400 mt-3 text-sm">Setiap peran memiliki autentikasi dan batasan hak akses yang aman di backend.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Role 1: Owner / Admin -->
                <div class="rounded-3xl border border-teal-500/30 bg-gradient-to-b from-teal-950/30 to-slate-900 p-8 flex flex-col justify-between relative group hover:border-teal-500 transition-all">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/20 text-teal-400 flex items-center justify-center text-xl mb-6">
                            <i class="fas fa-crown"></i>
                        </div>
                        <span class="text-[10px] font-bold text-teal-400 uppercase tracking-wider">Peran 1: Manajemen Usaha</span>
                        <h3 class="text-xl font-bold text-white mt-1">Pemilik IKM & Admin</h3>
                        <p class="text-xs text-slate-300 mt-3 leading-relaxed">
                            Melihat gambaran besar bisnis: stok balok masuk, ekspor laporan Excel/PDF keuangan, manajemen akun karyawan, dan pemanfaatan sisa potongan mozaik.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-slate-800">
                        @auth
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('dashboard') }}" class="w-full py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                                    <span>Buka Dashboard Admin</span> <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            @else
                                <span class="text-[11px] text-slate-500 text-center block">Khusus Hak Akses Admin</span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                                <i class="fas fa-lock text-xs"></i> <span>Login Sebagai Admin</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Role 2: Operator Bengkel -->
                <div class="rounded-3xl border border-amber-500/30 bg-gradient-to-b from-amber-950/30 to-slate-900 p-8 flex flex-col justify-between relative group hover:border-amber-500 transition-all">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl mb-6">
                            <i class="fas fa-hammer"></i>
                        </div>
                        <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider">Peran 2: Lantai Kerja (Shopfloor)</span>
                        <h3 class="text-xl font-bold text-white mt-1">Operator Bengkel / Perajin</h3>
                        <p class="text-xs text-slate-300 mt-3 leading-relaxed">
                            Diakses melalui HP dengan Wi-Fi bengkel. Tombol cepat satu sentuhan untuk memajukan tahapan: potong balok &rarr; bubut &rarr; resin &rarr; poles &rarr; packing. Akun dibuatkan oleh Admin.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-slate-800">
                        @auth
                            @if(Auth::user()->isOperator() || Auth::user()->isAdmin())
                                <a href="{{ route('operator.index') }}" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                                    <span>Buka Mode Operator</span> <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            @else
                                <span class="text-[11px] text-slate-500 text-center block">Khusus Operator Internal Bengkel</span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                                <i class="fas fa-lock text-xs"></i> <span>Login Sebagai Operator</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Role 3: Buyer / Pelanggan -->
                <div class="rounded-3xl border border-cyan-500/30 bg-gradient-to-b from-cyan-950/30 to-slate-900 p-8 flex flex-col justify-between relative group hover:border-cyan-500 transition-all">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl mb-6">
                            <i class="fas fa-bag-shopping"></i>
                        </div>
                        <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider">Peran 3: Pembeli / Mitra Eksternal</span>
                        <h3 class="text-xl font-bold text-white mt-1">Pelanggan / Buyer Publik</h3>
                        <p class="text-xs text-slate-300 mt-3 leading-relaxed">
                            Melihat katalog foto motif marmer tanpa login, konsultasi via WhatsApp, dan login untuk melakukan pemesanan custom terintegrasi profil alamat otomatis.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-slate-800 flex items-center gap-2">
                        <a href="{{ route('catalog') }}" class="flex-1 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                            <span>Katalog (Publik)</span>
                        </a>
                        <a href="{{ route('tracking.index') }}" class="flex-1 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 text-xs font-bold flex items-center justify-center gap-1.5 transition-all border border-slate-700">
                            <span>Lacak WIP</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4 Pilar Kebutuhan Sistem -->
    <section id="fitur" class="py-24 bg-slate-900/50 border-t border-slate-800/80">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-teal-400 text-xs font-bold tracking-widest uppercase">System Requirements Solved</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">4 Pilar E-Supply Chain IKM</h2>
                <p class="text-slate-400 mt-3 text-sm sm:text-base">Pembagian jelas antara modul inventaris internal (wajib login) dan layanan publik pembeli.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pilar 1: Inventory (Wajib Login) -->
                <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl hover:border-teal-500/50 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 text-xl mb-5 group-hover:scale-110 group-hover:bg-teal-500 group-hover:text-slate-950 transition-all">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <span class="text-[10px] text-teal-400 font-bold uppercase tracking-wider block mb-1">
                        <i class="fas fa-lock text-[9px] mr-1"></i> Wajib Login Internal
                    </span>
                    <h3 class="text-lg font-bold text-white mb-2">1. Inventory Bahan Baku</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">Digitalisasi dari buku catatan manual ke sistem. Klasifikasi balok Besole, TR, Merah, dan diameter Batu Kali.</p>
                    <a href="{{ route('bahan-baku.index') }}" class="text-xs font-semibold text-teal-400 hover:text-teal-300 flex items-center gap-1">Akses Inventaris <i class="fas fa-chevron-right text-[10px]"></i></a>
                </div>

                <!-- Pilar 2: Monitoring WIP (Dapat Diakses Tanpa Login) -->
                <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl hover:border-emerald-500/50 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-xl mb-5 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-slate-950 transition-all">
                        <i class="fas fa-magnifying-glass-location"></i>
                    </div>
                    <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider block mb-1">
                        <i class="fas fa-globe text-[9px] mr-1"></i> Publik Tanpa Login
                    </span>
                    <h3 class="text-lg font-bold text-white mb-2">2. Monitoring WIP Track</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">Pelacakan nomor resi pesanan: *Order Received &rarr; Cutting &rarr; Bubut &rarr; Polishing &rarr; Siap Kirim*.</p>
                    <a href="{{ route('tracking.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center gap-1">Lacak Pesanan Anda <i class="fas fa-chevron-right text-[10px]"></i></a>
                </div>

                <!-- Pilar 3: Showcase Katalog (Dapat Diakses Tanpa Login) -->
                <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl hover:border-cyan-500/50 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-xl mb-5 group-hover:scale-110 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all">
                        <i class="fas fa-images"></i>
                    </div>
                    <span class="text-[10px] text-cyan-400 font-bold uppercase tracking-wider block mb-1">
                        <i class="fas fa-globe text-[9px] mr-1"></i> Publik Tanpa Login
                    </span>
                    <h3 class="text-lg font-bold text-white mb-2">3. Showcase & Katalog SEO</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">Galeri motif detail untuk buyer luar kota/pulau. Meniadakan pengiriman foto WhatsApp berulang dan menarik pasar organik.</p>
                    <a href="{{ route('catalog') }}" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">Lihat Katalog HD <i class="fas fa-chevron-right text-[10px]"></i></a>
                </div>

                <!-- Pilar 4: Circular Economy Limbah (Wajib Login) -->
                <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl hover:border-amber-500/50 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xl mb-5 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-slate-950 transition-all">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <span class="text-[10px] text-amber-400 font-bold uppercase tracking-wider block mb-1">
                        <i class="fas fa-lock text-[9px] mr-1"></i> Wajib Login Internal
                    </span>
                    <h3 class="text-lg font-bold text-white mb-2">4. Sirkular Hilirisasi Limbah</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">Pencatatan dan monetisasi sisa potongan brongkolan mozaik, wall cladding, dan pasir urukan dengan sistem CRUD lengkap.</p>
                    <a href="{{ route('pemanfaatan-limbah.index') }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 flex items-center gap-1">Kelola Limbah <i class="fas fa-chevron-right text-[10px]"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Profil IKM Terdaftar (Poin 5: Verifikasi Login Diperlukan) -->
    <section id="ikm" class="py-24 bg-slate-950 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-teal-400 text-xs font-bold tracking-widest uppercase">Mitra Pengrajin</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Sentra Pengrajin Campurdarat</h2>
                <p class="text-slate-400 mt-3 text-sm">Data riil hasil observasi operasional dan rantai pasok industri marmer lokal.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- UD Cahaya Onix -->
                <div class="bg-gradient-to-b from-slate-900 to-slate-950 border border-slate-800 rounded-3xl p-8 hover:border-teal-500/40 transition-all">
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3 py-1 rounded-full bg-teal-500/10 text-teal-400 text-xs font-semibold border border-teal-500/20">Pengolahan Marmer</span>
                        <span class="text-xs text-slate-400"><i class="fas fa-map-marker-alt text-rose-400 mr-1"></i> Campurdarat, Tulungagung</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">UD Cahaya Onix</h3>
                    <p class="text-sm text-teal-300 font-medium mb-4">Pimpinan: M. Ilham Nur Amali</p>
                    <p class="text-xs text-slate-300 leading-relaxed mb-6">
                        Kapasitas: 14 biji/hari dengan 7 mesin bubut aktif. Mengolah marmer Besole putih, marmer merah, TR, dan marmer hitam menjadi wastafel, pedestal, dan dispenser sabun eksklusif.
                    </p>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-800/80">
                        <span class="text-xs text-slate-400">Model: <strong>Make-to-Order</strong></span>
                        @auth
                            <a href="{{ route('ikm.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-teal-500 hover:text-slate-950 text-xs font-semibold text-white transition-all">Detail Profil IKM</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-teal-500 hover:text-slate-950 text-xs font-semibold text-white transition-all flex items-center gap-1.5">
                                <i class="fas fa-lock text-[10px]"></i> Detail (Login)
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- UD Putra Abadi -->
                <div class="bg-gradient-to-b from-slate-900 to-slate-950 border border-slate-800 rounded-3xl p-8 hover:border-emerald-500/40 transition-all">
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-semibold border border-emerald-500/20">Pengolahan Batu Kali</span>
                        <span class="text-xs text-slate-400"><i class="fas fa-map-marker-alt text-rose-400 mr-1"></i> Campurdarat, Tulungagung</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">UD. Putra Abadi</h3>
                    <p class="text-sm text-emerald-300 font-medium mb-4">Pimpinan: Efri Saputra</p>
                    <p class="text-xs text-slate-300 leading-relaxed mb-6">
                        Pengrajin batu alam sungai alami untuk stepping stones taman, wastafel batu kali boulder, dan kap lampu artistik. Pemanfaatan sisa potongan samping menjadi wall cladding bernilai tinggi.
                    </p>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-800/80">
                        <span class="text-xs text-slate-400">Pengiriman: <strong>Jawa & Bali</strong></span>
                        @auth
                            <a href="{{ route('ikm.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-emerald-500 hover:text-slate-950 text-xs font-semibold text-white transition-all">Detail Profil IKM</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-emerald-500 hover:text-slate-950 text-xs font-semibold text-white transition-all flex items-center gap-1.5">
                                <i class="fas fa-lock text-[10px]"></i> Detail (Login)
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Bersih & Sederhana (Poin 6: Menu Berulang Dihapus) -->
    <footer class="py-10 bg-slate-950 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-500 flex items-center justify-center text-slate-950 font-bold">
                    <i class="fas fa-gem text-sm"></i>
                </div>
                <span class="text-sm font-bold text-white">MarmerSC &bull; E-Supply Chain IKM Campurdarat</span>
            </div>
            <p class="text-xs text-slate-500 text-center md:text-left">&copy; 2026 Sistem Informasi E-Supply Chain Rantai Pasok Marmer Tulungagung. All rights reserved.</p>
            <div class="flex items-center gap-3 text-xs text-slate-400">
                <a href="https://wa.me/6281234567890?text=Halo%20Perajin%20Marmer" target="_blank" class="text-emerald-400 hover:underline flex items-center gap-1">
                    <i class="fab fa-whatsapp"></i> WhatsApp Pengrajin
                </a>
            </div>
        </div>
    </footer>
</body>
</html>
