<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Pelanggan (Buyer) - E-Supply Chain Marmer Tulungagung</title>
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

    <div class="max-w-lg w-full mx-auto flex items-center justify-between mb-4">
        <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white shadow-md">
                <i class="fas fa-cubes-stacked text-base"></i>
            </div>
            <div>
                <span class="text-lg font-black text-white">MarmerSC</span>
                <span class="block text-[9px] text-teal-400 font-bold uppercase">Registrasi Pelanggan</span>
            </div>
        </a>
        <a href="{{ route('login') }}" class="text-xs text-teal-400 hover:underline">
            Sudah Punya Akun? Masuk
        </a>
    </div>

    <div class="max-w-lg w-full mx-auto bg-slate-900/80 border border-slate-800 rounded-3xl p-8 backdrop-blur-2xl shadow-2xl relative overflow-hidden">
        <div class="mb-6">
            <span class="text-[10px] font-bold uppercase tracking-wider text-teal-400 bg-teal-500/10 px-3 py-1 rounded-full border border-teal-500/20">
                Akun Pelanggan (Buyer)
            </span>
            <h1 class="text-2xl font-black text-white mt-2">Daftar Akun Baru</h1>
            <p class="text-xs text-slate-400 mt-1">
                Pendaftaran mandiri khusus untuk Pembeli / Pelanggan marmer. Akun Admin dan Operator internal dikelola langsung oleh Administrator bengkel.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Info Box Otomatis Role Buyer -->
            <div class="p-3.5 rounded-2xl bg-teal-950/40 border border-teal-500/30 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0">
                    <i class="fas fa-bag-shopping text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-white block">Tipe Akun: Pelanggan / Buyer</span>
                    <span class="text-[10px] text-teal-300/80">Katalog produk, pemesanan custom terintegrasi, dan lacak progres WIP mandiri.</span>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Maya Lestari" required class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">No. WhatsApp / HP</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" required class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Alamat Lengkap Pengiriman</label>
                <textarea name="alamat" rows="2" placeholder="Contoh: Jl. Danau Tamblingan No. 42, Sanur, Denpasar, Bali" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('alamat') }}</textarea>
                <span class="text-[10px] text-slate-500 block mt-0.5">Alamat ini otomatis digunakan saat Anda memesan marmer, tanpa perlu mengetik ulang.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" required class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Ulangi Sandi</label>
                    <input type="password" name="password_confirmation" placeholder="Ketik ulang sandi" required class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-2xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-black text-xs shadow-lg shadow-teal-500/25 transition-all mt-2">
                Daftar Sebagai Pelanggan
            </button>
        </form>

        <p class="text-center text-xs text-slate-400 mt-6">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="text-teal-400 font-bold hover:underline">Masuk Disini</a>
        </p>
    </div>

    <div class="text-center text-[11px] text-slate-600 mt-6">
        &copy; 2026 E-Supply Chain IKM Marmer & Batu Alam Tulungagung
    </div>

</body>
</html>
