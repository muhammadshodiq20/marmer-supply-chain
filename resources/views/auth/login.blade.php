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

        <!-- Form Login Mandiri -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Email / Username Akun</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="text" name="email" value="{{ old('email') }}" placeholder="admin / operator / buyer / email..." required autocapitalize="none" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                    <input type="password" name="password" placeholder="Masukkan kata sandi..." required class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-300">
                    <input type="checkbox" name="remember" class="rounded bg-slate-950 border-slate-700 text-teal-500 focus:ring-0">
                    <span>Ingat Saya</span>
                </label>
                <a href="#" class="text-teal-400 hover:underline">Lupa Sandi?</a>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-black text-xs shadow-lg shadow-teal-500/25 transition-all">
                Masuk ke Akun
            </button>
        </form>

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
