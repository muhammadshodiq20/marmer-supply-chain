@extends('layouts.app')

@section('title', 'Profil & Pesanan Saya')
@section('page-title', 'Pesanan Saya & Profil Akun')
@section('page-subtitle', 'Kelola Pesanan Marmer, Riwayat Pembayaran, dan Data Pengiriman')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- DAFTAR PESANAN SAYA (Semua Riwayat Transaksi Tersimpan Aman) -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm p-6 space-y-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-extrabold text-lg text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-receipt text-teal-500"></i> Riwayat Pesanan Saya
                </h3>
                <p class="text-xs text-gray-400">Seluruh transaksi (aktif maupun selesai) tersimpan secara permanen di akun Anda.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('catalog') }}" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold shadow-md shadow-teal-500/20 flex items-center gap-1.5 transition-all">
                    <i class="fas fa-bag-shopping"></i> Belanja dari Katalog
                </a>
                <a href="{{ route('pesanan.public_create') }}" class="px-3.5 py-2 rounded-xl border border-slate-700 hover:border-teal-500 text-teal-400 text-xs font-bold transition-all">
                    <i class="fas fa-wand-magic-sparkles mr-1"></i> Pesan Kustom
                </a>
            </div>
        </div>

        <!-- Filter Tab Kategori Pesanan (Poin 3) -->
        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100 dark:border-slate-800 text-xs">
            <a href="{{ route('buyer.profile') }}" class="px-3 py-1.5 rounded-xl font-bold transition-colors {{ !request('status') ? 'bg-teal-500 text-slate-950' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Semua ({{ $counts['total'] }})
            </a>
            <a href="{{ route('buyer.profile', ['status' => 'menunggu_bayar']) }}" class="px-3 py-1.5 rounded-xl font-bold transition-colors {{ request('status') === 'menunggu_bayar' ? 'bg-amber-500 text-slate-950' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Menunggu Bayar ({{ $counts['menunggu_bayar'] }})
            </a>
            <a href="{{ route('buyer.profile', ['status' => 'menunggu_verifikasi']) }}" class="px-3 py-1.5 rounded-xl font-bold transition-colors {{ request('status') === 'menunggu_verifikasi' ? 'bg-purple-500 text-white' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Menunggu Verifikasi ({{ $counts['menunggu_verifikasi'] }})
            </a>
            <a href="{{ route('buyer.profile', ['status' => 'diproses']) }}" class="px-3 py-1.5 rounded-xl font-bold transition-colors {{ request('status') === 'diproses' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Diproses di Bengkel ({{ $counts['diproses'] }})
            </a>
            <a href="{{ route('buyer.profile', ['status' => 'dikirim']) }}" class="px-3 py-1.5 rounded-xl font-bold transition-colors {{ request('status') === 'dikirim' ? 'bg-cyan-500 text-slate-950' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Dikirim ({{ $counts['dikirim'] }})
            </a>
            <a href="{{ route('buyer.profile', ['status' => 'selesai']) }}" class="px-3 py-1.5 rounded-xl font-bold transition-colors {{ request('status') === 'selesai' ? 'bg-emerald-500 text-white' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Pesanan Selesai ({{ $counts['selesai'] }})
            </a>
        </div>

        <!-- Daftar Kartu Pesanan -->
        <div class="space-y-4 pt-2">
            @forelse($myOrders as $order)
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-800/60 border border-gray-200 dark:border-slate-700/80 hover:border-teal-500/50 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                    <div class="space-y-1.5 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-xs font-black text-teal-600 dark:text-teal-400">{{ $order->kode_order }}</span>
                            <span class="text-xs text-gray-400">&bull; {{ $order->tanggal_order?->format('d M Y') }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $order->tipe_pesanan === 'katalog' ? 'bg-cyan-100 text-cyan-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ $order->tipe_pesanan === 'katalog' ? 'Katalog Ready' : 'Pesan Kustom' }}
                            </span>
                            <!-- Badge Status Sinkron -->
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                            <!-- Badge Status Pembayaran -->
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $order->status_pembayaran === 'lunas' ? 'bg-emerald-100 text-emerald-700' : ($order->status_pembayaran === 'menunggu_verifikasi' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ strtoupper(str_replace('_', ' ', $order->status_pembayaran)) }}
                            </span>
                        </div>

                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">
                            {{ $order->jenis_produk }}
                            <span class="text-xs font-normal text-gray-500">({{ $order->jumlah }} unit)</span>
                        </h4>

                        <div class="flex flex-wrap items-center gap-x-4 text-xs text-gray-500">
                            <span>Sentra IKM: <strong class="text-gray-700 dark:text-gray-300">{{ $order->ikmProfile->nama_usaha }}</strong></span>
                            <span>Total Pembayaran: <strong class="text-emerald-600 dark:text-emerald-400 font-extrabold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @if($order->status_pembayaran !== 'lunas')
                            <a href="{{ route('pembayaran.show', $order) }}" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                                <i class="fas fa-credit-card"></i> Bayar / Unggah Bukti
                            </a>
                        @endif

                        <a href="{{ route('buyer.orders.show', $order) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-teal-300 text-xs font-bold border border-slate-700 shadow-sm flex items-center gap-1.5 transition-all">
                            <i class="fas fa-eye"></i> Rincian Pesanan
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-gray-400 text-xs glass rounded-2xl border border-dashed border-gray-300 dark:border-slate-700">
                    <i class="fas fa-receipt text-3xl mb-2 text-gray-400"></i>
                    <p class="font-bold">Tidak ada pesanan pada kategori ini.</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Semua pesanan yang pernah Anda lakukan akan tetap tersimpan di halaman ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- PENGATURAN PROFIL & ALAMAT PENGIRIMAN -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
        <!-- Kartu Identitas Ringkas -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm flex flex-col items-center text-center">
            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-teal-500/30 shadow-md mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>

            <span class="mt-3 px-3.5 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-900/40 text-teal-600 dark:text-teal-300 border border-teal-500/20">
                {{ $user->role_label }}
            </span>

            <div class="w-full mt-6 pt-6 border-t border-gray-100 dark:border-slate-800 text-left space-y-2 text-xs">
                <div>
                    <span class="text-gray-400 font-semibold block uppercase text-[10px]">No. WhatsApp:</span>
                    <span class="font-bold text-gray-700 dark:text-gray-200">{{ $user->telepon ?? 'Belum diisi' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase text-[10px]">Alamat Pengiriman Utama:</span>
                    <span class="text-gray-700 dark:text-gray-200">{{ $user->alamat ?? 'Belum ada alamat' }}</span>
                </div>
            </div>
        </div>

        <!-- Formulir Edit Profil -->
        <div class="md:col-span-2 glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                <i class="fas fa-user-pen text-teal-500"></i> Perbarui Profil & Alamat
            </h3>
            <p class="text-xs text-gray-400 mb-6">
                Data alamat dan nomor WhatsApp ini otomatis terisi saat membeli produk marmer dari katalog.
            </p>

            <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Alamat Email (Akun)</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 rounded-xl text-xs bg-gray-100 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Nomor WhatsApp / HP</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}" placeholder="08xxxxxxxxxx" required class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Alamat Lengkap Pengiriman</label>
                    <textarea name="alamat" rows="2.5" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten..." required class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">{{ old('alamat', $user->alamat) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Ganti Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-slate-800">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-2">Ganti Kata Sandi (Kosongkan bila tidak ingin mengubah)</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="password" name="password" placeholder="Kata sandi baru" class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru" class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
