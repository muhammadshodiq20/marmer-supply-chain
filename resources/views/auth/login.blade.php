<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - E-Supply Chain IKM Marmer Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-pattern {
            background-color: #0b1329;
            background-image: radial-gradient(at 0% 0%, rgba(20, 184, 166, 0.25) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.2) 0px, transparent 50%),
                              radial-gradient(at 50% 50%, rgba(15, 118, 110, 0.15) 0px, transparent 50%);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen hero-pattern flex flex-col justify-between p-6">

    <!-- Top Brand -->
    <div class="max-w-md w-full mx-auto flex items-center justify-between mb-4">
        <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white shadow-md">
                <i class="fas fa-cubes-stacked text-base"></i>
            </div>
            <div>
                <span class="text-lg font-black text-white">MarmerSC</span>
                <span class="block text-[9px] text-teal-400 font-bold uppercase">Tulungagung</span>
            </div>
        </a>
        <a href="{{ route('landing') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1">
            <i class="fas fa-arrow-left text-[10px]"></i> Beranda
        </a>
    </div>

    <!-- Main Login Card -->
    <div class="max-w-md w-full mx-auto bg-slate-900/80 border border-slate-800 rounded-3xl p-8 backdrop-blur-2xl shadow-2xl relative overflow-hidden">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-black text-white tracking-tight">Selamat Datang</h1>
            <p class="text-xs text-slate-400 mt-1">Masuk ke Portal E-Supply Chain Marmer Tulungagung</p>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- One-Click Google Login Button -->
        <a href="{{ route('auth.google') }}" class="w-full py-3 px-4 rounded-2xl bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs shadow-md transition-all flex items-center justify-center gap-3 border border-slate-200 hover:scale-[1.01] active:scale-[0.99] mb-5">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Lanjutkan dengan Google</span>
        </a>

        <div class="relative flex items-center justify-center mb-5">
            <div class="border-t border-slate-800 w-full"></div>
            <span class="bg-slate-900 px-3 text-[10px] text-slate-500 uppercase font-semibold">atau dengan email</span>
            <div class="border-t border-slate-800 w-full"></div>
        </div>

        <!-- Form Email & Password -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Alamat Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="password" name="password" placeholder="••••••••" required class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-400">
                    <input type="checkbox" name="remember" class="rounded bg-slate-950 border-slate-700 text-teal-500 focus:ring-0">
                    <span>Ingat Saya</span>
                </label>
                <a href="#" class="text-teal-400 hover:underline">Lupa Sandi?</a>
            </div>

            <button type="submit" class="w-full py-3 rounded-2xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-black text-xs shadow-lg shadow-teal-500/25 transition-all">
                Masuk ke Akun
            </button>
        </form>

        <!-- Quick 1-Click Role Login (Bagus untuk Uji Coba & Presentasi Magang) -->
        <div class="mt-6 pt-5 border-t border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2 text-center">
                Demo Login Instan (1-Klik):
            </span>
            <div class="grid grid-cols-3 gap-2">
                <a href="{{ route('auth.quick', 'admin') }}" class="p-2 rounded-xl bg-teal-500/10 hover:bg-teal-500/20 border border-teal-500/30 text-teal-400 text-center transition-all">
                    <i class="fas fa-crown text-xs block mb-1"></i>
                    <span class="text-[10px] font-bold block">Admin</span>
                </a>
                <a href="{{ route('auth.quick', 'operator') }}" class="p-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 text-center transition-all">
                    <i class="fas fa-hammer text-xs block mb-1"></i>
                    <span class="text-[10px] font-bold block">Operator</span>
                </a>
                <a href="{{ route('auth.quick', 'buyer') }}" class="p-2 rounded-xl bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 text-cyan-400 text-center transition-all">
                    <i class="fas fa-bag-shopping text-xs block mb-1"></i>
                    <span class="text-[10px] font-bold block">Buyer</span>
                </a>
            </div>
        </div>

        <!-- Footer Link -->
        <p class="text-center text-xs text-slate-400 mt-6">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="text-teal-400 font-bold hover:underline">Daftar Sekarang</a>
        </p>
    </div>

    <!-- Footer -->
    <div class="text-center text-[11px] text-slate-600 mt-6">
        &copy; 2026 E-Supply Chain IKM Marmer & Batu Alam Tulungagung
    </div>

</body>
</html>
