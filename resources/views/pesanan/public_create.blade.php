<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kustom Marmer & Batu Alam - IKM Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen selection:bg-teal-500 selection:text-white py-12 px-6">

    <div class="max-w-3xl mx-auto">
        <!-- Top Navigation -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white">
                    <i class="fas fa-cubes-stacked"></i>
                </div>
                <span class="text-xl font-extrabold text-white">MarmerSC</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('catalog') }}" class="text-xs font-semibold text-slate-400 hover:text-white">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Katalog
                </a>
                <a href="https://wa.me/6281234567890?text=Halo%20Perajin%20Marmer%20Tulungagung,%20saya%20ingin%20konsultasi%20pemesanan%20batu%20marmer." target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center gap-1.5 shadow-md">
                    <i class="fab fa-whatsapp"></i> Tanya via WA (Bebas Login)
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 shadow-2xl backdrop-blur-xl">
            <div class="mb-8">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20 uppercase tracking-wider">
                        Make-to-Order Request
                    </span>
                    <span class="text-xs text-slate-400">
                        Login sebagai: <strong class="text-white">{{ auth()->user()->name }}</strong>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white mt-3">Formulir Pesanan Kustom Marmer</h1>
                <p class="text-xs text-slate-400 mt-1">
                    Data nama, kontak, dan alamat Anda diambil otomatis dari profil akun. Anda cukup menentukan spesifikasi marmer yang diinginkan.
                </p>
            </div>

            <!-- Box Data Profil Buyer Terintegrasi -->
            <div class="mb-6 p-4 rounded-2xl bg-slate-950 border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border border-teal-500/30">
                    <div>
                        <span class="text-xs font-bold text-white block">{{ auth()->user()->name }}</span>
                        <span class="text-[11px] text-teal-400">{{ auth()->user()->telepon ?? 'Nomor HP belum diisi' }}</span>
                        <p class="text-[10px] text-slate-400 mt-0.5 truncate max-w-sm">{{ auth()->user()->alamat ?? 'Alamat pengiriman belum diisi' }}</p>
                    </div>
                </div>
                <a href="{{ route('buyer.profile') }}" class="px-3 py-1.5 rounded-xl border border-slate-700 hover:border-teal-500 text-teal-300 text-xs font-semibold shrink-0 transition-colors">
                    <i class="fas fa-pen text-[10px] mr-1"></i> Edit Alamat Profil
                </a>
            </div>

            <form action="{{ route('pesanan.public_store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pilih Sentra Pengrajin</label>
                        <select name="ikm_profile_id" required class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-teal-500">
                            @foreach($ikmProfiles as $ikm)
                                <option value="{{ $ikm->id }}">{{ $ikm->nama_usaha }} ({{ $ikm->fokus_produksi }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Jenis / Kategori Produk Marmer</label>
                        <input type="text" name="jenis_produk" placeholder="Contoh: Wastafel Mangkok Marmer Besole / Stepping Batu Kali" required class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Jumlah Unit (Volume Pesanan)</label>
                        <input type="number" name="jumlah" value="1" min="1" required class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Kota / Wilayah Tujuan Pengiriman</label>
                        <input type="text" name="alamat_pengiriman" value="{{ auth()->user()->alamat }}" placeholder="Contoh: Denpasar Bali / Surabaya / Jakarta" required class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Spesifikasi Kustom Corak & Dimensi Ukuran</label>
                    <textarea name="spesifikasi" rows="4" placeholder="Jelaskan kebutuhan Anda: contohnya marmer putih Besole bersih tanpa serat hitam, diameter 40 cm, tinggi 15 cm, bibir wastafel membulat halus, lubang afur drainase 4.5 cm..." required class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-teal-500"></textarea>
                    <span class="text-[10px] text-slate-500 mt-1 block">Perajin marmer Tulungagung akan memotong dan membubut balok batu sesuai spesifikasi di atas.</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Catatan Tambahan / Kebutuhan Khusus</label>
                    <textarea name="catatan" rows="2" placeholder="Contoh: Pengemasan dengan peti kayu ekstra tebal untuk kargo laut, deadline tiba di Bali tanggal..." class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-slate-700 text-white text-xs focus:ring-2 focus:ring-teal-500"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[11px] text-slate-400 text-center sm:text-left">
                        <i class="fas fa-truck text-teal-400 mr-1"></i> Ongkos kirim cargo disepakati langsung dengan perajin saat verifikasi.
                    </p>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-black text-xs shadow-lg shadow-teal-500/30 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Pesanan Kustom
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
