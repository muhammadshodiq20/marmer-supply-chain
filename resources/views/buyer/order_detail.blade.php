@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->kode_order)
@section('page-title', 'Rincian Pesanan & Pembayaran')
@section('page-subtitle', 'Informasi Lengkap Transaksi, Verifikasi Pelunasan, dan Status Produksi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('buyer.profile') }}" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Kembali ke Pesanan Saya
        </a>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->status_color }}">
                Status Pengerjaan: {{ $order->status_label }}
            </span>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->status_pembayaran === 'lunas' ? 'bg-emerald-100 text-emerald-700' : ($order->status_pembayaran === 'menunggu_verifikasi' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700') }}">
                Pembayaran: {{ strtoupper(str_replace('_', ' ', $order->status_pembayaran)) }}
            </span>
        </div>
    </div>

    <!-- Kotak Utama Ringkasan Transaksi -->
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-gray-100 dark:border-slate-800">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400">Nomor Pesanan:</span>
                <h2 class="text-2xl font-mono font-black text-gray-900 dark:text-white">{{ $order->kode_order }}</h2>
                <span class="text-xs text-gray-400">Tanggal Order: {{ $order->tanggal_order?->format('d F Y') }}</span>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Total Tagihan:</span>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-gray-500 block">Metode: {{ strtoupper(str_replace('_', ' ', $order->metode_pembayaran ?? 'Transfer Bank')) }}</span>
            </div>
        </div>

        <!-- Rincian Produk & Pengrajin -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-2 p-4 rounded-2xl bg-gray-50/70 dark:bg-slate-800/40 border border-gray-100 dark:border-slate-800">
                <span class="font-bold text-gray-400 uppercase text-[10px] block">Produk Kerajinan:</span>
                <h4 class="text-base font-bold text-gray-900 dark:text-white">{{ $order->jenis_produk }}</h4>
                <p class="text-gray-500">Jumlah: <strong class="text-gray-900 dark:text-white">{{ $order->jumlah }} pcs / unit</strong></p>
                <p class="text-gray-500">Sentra Pengrajin: <strong class="text-teal-600">{{ $order->ikmProfile->nama_usaha }}</strong></p>
                @if($order->spesifikasi)
                    <p class="text-gray-600 dark:text-gray-300 italic pt-1 border-t border-gray-200 dark:border-slate-700">"{{ $order->spesifikasi }}"</p>
                @endif
            </div>

            <div class="space-y-2 p-4 rounded-2xl bg-gray-50/70 dark:bg-slate-800/40 border border-gray-100 dark:border-slate-800">
                <span class="font-bold text-gray-400 uppercase text-[10px] block">Tujuan Pengiriman:</span>
                <p class="font-bold text-gray-900 dark:text-white">{{ $order->nama_pelanggan }} ({{ $order->telepon_pelanggan }})</p>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $order->alamat_pengiriman }}</p>
                @if($order->biaya_pengiriman > 0)
                    <p class="text-gray-500 pt-1 border-t border-gray-200 dark:border-slate-700">Biaya Packing & Kirim: <strong>Rp {{ number_format($order->biaya_pengiriman, 0, ',', '.') }}</strong></p>
                @endif
            </div>
        </div>

        <!-- RIWAYAT VERIFIKASI PEMBAYARAN OLEH ADMIN (Poin 1, 3, 5) -->
        <div class="p-5 rounded-2xl border {{ $order->status_pembayaran === 'lunas' ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-amber-500/30 bg-amber-500/5' }}">
            <div class="flex items-center gap-2 mb-3">
                <i class="fas {{ $order->status_pembayaran === 'lunas' ? 'fa-circle-check text-emerald-500' : 'fa-clock text-amber-500' }} text-base"></i>
                <h4 class="font-bold text-xs uppercase tracking-wider text-gray-900 dark:text-white">
                    Status & Riwayat Verifikasi Pembayaran
                </h4>
            </div>

            @if($order->status_pembayaran === 'lunas')
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Diverifikasi Oleh:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $order->diverifikasi_oleh ?? 'Admin Sentra IKM' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Waktu Verifikasi:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $order->tanggal_verifikasi?->translatedFormat('d F Y, H:i') ?? '-' }} WIB</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Nominal Terverifikasi:</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($order->nominal_verifikasi ?? $order->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
                @if($order->catatan_verifikasi)
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-2 bg-emerald-100/50 dark:bg-emerald-950/40 p-2.5 rounded-xl">
                        <i class="fas fa-info-circle mr-1"></i> Catatan Admin: {{ $order->catatan_verifikasi }}
                    </p>
                @endif
            @elseif($order->status_pembayaran === 'menunggu_verifikasi')
                <div class="text-xs text-purple-700 dark:text-purple-300">
                    <p class="font-bold">Bukti transfer sudah diunggah pada {{ $order->tanggal_pembayaran?->format('d M Y H:i') }} WIB.</p>
                    <p class="text-gray-500 mt-0.5">Admin sedang memeriksa mutasi rekening. Anda akan menerima notifikasi otomatis begitu pelunasan disetujui.</p>
                </div>
            @else
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <p class="font-bold text-amber-700 dark:text-amber-400">Menunggu Pembayaran Pelunasan</p>
                        <p class="text-gray-500">Silakan lakukan transfer ke rekening resmi IKM lalu unggah bukti transfer.</p>
                    </div>
                    <a href="{{ route('pembayaran.show', $order) }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm flex items-center gap-1.5">
                        <i class="fas fa-upload"></i> Unggah Bukti Bayar
                    </a>
                </div>
            @endif
        </div>

        <!-- Timeline Perjalanan WIP Pesanan -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-800">
            <h4 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-2">
                <i class="fas fa-list-timeline text-teal-500"></i> Riwayat Tahapan Pengerjaan di Bengkel
            </h4>

            <div class="divide-y divide-gray-100 dark:divide-slate-800 text-xs">
                @forelse($order->productionStages as $stage)
                    <div class="py-3 flex items-start justify-between">
                        <div>
                            <span class="font-bold text-teal-600 dark:text-teal-400 uppercase text-[11px]">
                                {{ \App\Models\Order::STATUS_LABELS[$stage->tahap] ?? $stage->tahap }}
                            </span>
                            <p class="text-gray-500 mt-0.5">Petugas: <strong>{{ $stage->operator ?? 'Sistem' }}</strong></p>
                            @if($stage->catatan)
                                <p class="text-gray-600 dark:text-gray-300 mt-1 italic">"{{ $stage->catatan }}"</p>
                            @endif
                        </div>
                        <span class="text-[10px] text-gray-400 shrink-0">{{ $stage->created_at->format('d M Y H:i') }} WIB</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-xs py-2">Belum ada riwayat tahapan.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
