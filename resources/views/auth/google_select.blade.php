<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk dengan Google - E-Supply Chain Marmer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4 selection:bg-blue-500 selection:text-white">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <!-- Google Header -->
        <div class="p-6 text-center border-b border-slate-100">
            <svg class="w-10 h-10 mx-auto mb-3" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <h1 class="text-xl font-bold text-slate-800">Pilih Akun Google</h1>
            <p class="text-xs text-slate-500 mt-1">untuk melanjutkan ke <strong class="text-slate-700">MarmerSC Tulungagung</strong></p>
        </div>

        <!-- Daftar Pilihan Akun Google Asli / Cepat -->
        <div class="divide-y divide-slate-100">
            <!-- Akun 1: Maya Lestari (Buyer) -->
            <form action="{{ route('auth.google.select') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="name" value="Maya Lestari">
                <input type="hidden" name="email" value="maya.lestari@gmail.com">
                <input type="hidden" name="avatar" value="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80">
                <button type="submit" class="w-full p-4 flex items-center gap-3.5 hover:bg-slate-50 transition-colors text-left group">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80" alt="Maya Lestari" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 block leading-tight">Maya Lestari</span>
                        <span class="text-xs text-slate-500 block">maya.lestari@gmail.com</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-blue-500"></i>
                </button>
            </form>

            <!-- Akun 2: Budi Santoso (Buyer Arsitek) -->
            <form action="{{ route('auth.google.select') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="name" value="Budi Santoso">
                <input type="hidden" name="email" value="budi.arsitek@gmail.com">
                <input type="hidden" name="avatar" value="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80">
                <button type="submit" class="w-full p-4 flex items-center gap-3.5 hover:bg-slate-50 transition-colors text-left group">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80" alt="Budi Santoso" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 block leading-tight">Budi Santoso (Arsitek Bali)</span>
                        <span class="text-xs text-slate-500 block">budi.arsitek@gmail.com</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-blue-500"></i>
                </button>
            </form>

            <!-- Opsi 3: Masukkan Akun Google Milik Anda Sendiri -->
            <div class="p-5 bg-slate-50/60" x-data="{ customGoogle: false }">
                <button type="button" @click="customGoogle = !customGoogle" class="w-full flex items-center gap-3 text-left text-xs font-semibold text-blue-600 hover:text-blue-700">
                    <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 text-sm">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <span class="block text-slate-800 font-bold">Gunakan Akun Google Lainnya</span>
                        <span class="text-[11px] text-slate-500">Ketik nama & email Google Anda sendiri</span>
                    </div>
                </button>

                <!-- Form Ketik Akun Google Pribadi -->
                <form x-show="customGoogle" x-transition action="{{ route('auth.google.select') }}" method="POST" class="mt-4 pt-4 border-t border-slate-200 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Nama Akun Google Anda</label>
                        <input type="text" name="name" placeholder="Nama Lengkap Anda" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Alamat Email Google (@gmail.com)</label>
                        <input type="email" name="email" placeholder="namaanda@gmail.com" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5">
                        <i class="fab fa-google"></i> Masuk dengan Akun Ini
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <a href="{{ route('login') }}" class="hover:text-slate-800">
                <i class="fas fa-arrow-left mr-1"></i> Batal
            </a>
            <span class="text-[11px] text-slate-400">Google OAuth 2.0 Client UI</span>
        </div>
    </div>

    <!-- Script Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
