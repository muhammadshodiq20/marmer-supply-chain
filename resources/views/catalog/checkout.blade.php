@extends('layouts.app')

@section('title', 'Checkout Pembelian Produk')
@section('page-title', 'Checkout Pembelian Produk')
@section('page-subtitle', 'Konfirmasi Pesanan Barang Ready Stock dari Katalog Marmer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    qty: 1,
    unitPrice: {{ (int)($product->harga ?? 450000) }},
    shippingCost: 75000,
    get subtotal() { return this.qty * this.unitPrice; },
    get grandTotal() { return this.subtotal + this.shippingCost; },
    formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
}">

    <div class="flex items-center justify-between">
        <a href="{{ route('catalog') }}" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Kembali ke Katalog
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 border border-teal-500/20">
            <i class="fas fa-box mr-1"></i> Produk Ready Stock
        </span>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('catalog.checkout.process', $product) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @csrf

        <!-- Kolom Kiri: Rincian Produk & Pengiriman (2/3) -->
        <div class="md:col-span-2 space-y-6">
            <!-- Box Ringkasan Barang yang Dibeli -->
            <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-bag-shopping text-teal-500"></i> Barang yang Dibeli
                </h3>

                <div class="flex flex-col sm:flex-row items-center gap-4 pb-6 border-b border-gray-100 dark:border-slate-800">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-900 shrink-0">
                        <img src="{{ $product->image_url }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80';">
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400">{{ $product->jenis_batu }}</span>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white mt-0.5">{{ $product->nama_produk }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Sentra: <strong>{{ $product->ikmProfile->nama_usaha }}</strong></p>
                        <span class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400 block mt-1">
                            {{ $product->harga ? 'Rp ' . number_format($product->harga, 0, ',', '.') : 'Rp 450.000' }} / pcs
                        </span>
                    </div>

                    <!-- Counter Jumlah Pembelian -->
                    <div class="flex items-center gap-2 border border-gray-200 dark:border-slate-700 rounded-xl p-1 bg-white dark:bg-slate-800">
                        <button type="button" @click="if(qty > 1) qty--" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 flex items-center justify-center text-xs font-bold">
                            -
                        </button>
                        <input type="number" name="jumlah" x-model.number="qty" min="1" class="w-12 text-center text-xs font-bold bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white" required>
                        <button type="button" @click="qty++" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 flex items-center justify-center text-xs font-bold">
                            +
                        </button>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between text-xs text-gray-500">
                    <span>Subtotal Produk:</span>
                    <span class="font-bold text-gray-900 dark:text-white" x-text="formatRupiah(subtotal)"></span>
                </div>
            </div>

            <!-- Box Alamat Pengiriman (Otomatis dari Profil) -->
            <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-location-dot text-rose-500"></i> Alamat & Penerima (Data Profil)
                    </h3>
                    <a href="{{ route('buyer.profile') }}" class="text-[11px] text-teal-600 dark:text-teal-400 font-semibold hover:underline">
                        Ubah di Profil
                    </a>
                </div>

                <div class="p-3.5 rounded-2xl bg-teal-50/50 dark:bg-teal-950/20 border border-teal-500/20 flex items-center gap-3">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-teal-500/40">
                    <div>
                        <span class="text-xs font-bold text-gray-900 dark:text-white block">{{ $user->name }}</span>
                        <span class="text-[11px] text-gray-500">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">No. WhatsApp / HP Penerima</label>
                        <input type="text" name="telepon_pelanggan" value="{{ old('telepon_pelanggan', $user->telepon) }}" placeholder="08xxxxxxxxxx" required class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Pesanan (Opsional)</label>
                        <input type="text" name="catatan" placeholder="Contoh: Tolong pilih corak yang uratnya sedikit" class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap Pengiriman</label>
                    <textarea name="alamat_pengiriman" rows="2.5" required placeholder="Jalan, No Rumah, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos..." class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">{{ old('alamat_pengiriman', $user->alamat) }}</textarea>
                </div>
            </div>

            <!-- Box Pilihan Metode Pembayaran -->
            <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fas fa-credit-card text-teal-500"></i> Pilih Metode Pembayaran
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="p-3.5 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 flex flex-col justify-between cursor-pointer hover:border-teal-500 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Bank BCA</span>
                            <input type="radio" name="metode_pembayaran" value="transfer_bca" checked class="text-teal-600 focus:ring-0">
                        </div>
                        <span class="text-[10px] text-gray-400">Transfer Manual & Unggah Bukti</span>
                    </label>

                    <label class="p-3.5 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 flex flex-col justify-between cursor-pointer hover:border-teal-500 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Bank Mandiri</span>
                            <input type="radio" name="metode_pembayaran" value="transfer_mandiri" class="text-teal-600 focus:ring-0">
                        </div>
                        <span class="text-[10px] text-gray-400">Transfer Manual & Unggah Bukti</span>
                    </label>

                    <label class="p-3.5 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 flex flex-col justify-between cursor-pointer hover:border-teal-500 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-900 dark:text-white">COD / Tunai</span>
                            <input type="radio" name="metode_pembayaran" value="cod" class="text-teal-600 focus:ring-0">
                        </div>
                        <span class="text-[10px] text-gray-400">Bayar di Tempat (Khusus Area Tertentu)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Pembayaran & Tombol Order (1/3) -->
        <div class="space-y-6">
            <div class="glass p-6 rounded-3xl border border-teal-500/30 shadow-xl bg-gradient-to-b from-teal-500/5 to-transparent sticky top-24">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 pb-3 border-b border-gray-100 dark:border-slate-800">
                    Ringkasan Pembayaran
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span>Harga Satuan:</span>
                        <span class="font-semibold" x-text="formatRupiah(unitPrice)"></span>
                    </div>

                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span>Kuantitas:</span>
                        <span class="font-bold text-gray-900 dark:text-white" x-text="qty + ' pcs'"></span>
                    </div>

                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span>Subtotal Produk:</span>
                        <span class="font-semibold text-gray-900 dark:text-white" x-text="formatRupiah(subtotal)"></span>
                    </div>

                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span>Ekspedisi & Peti Kayu:</span>
                        <span class="font-semibold text-gray-900 dark:text-white" x-text="formatRupiah(shippingCost)"></span>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-gray-800 dark:text-gray-200">Total Pembayaran:</span>
                        <span class="text-lg font-black text-emerald-600 dark:text-emerald-400" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800 space-y-3">
                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-500 text-white font-extrabold text-xs shadow-lg shadow-teal-500/25 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Selesaikan Pesanan
                    </button>

                    <p class="text-[10px] text-gray-400 text-center leading-relaxed">
                        Setelah pesanan dibuat, Anda akan diarahkan ke halaman konfirmasi rekening bank & unggah bukti transfer.
                    </p>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
