@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran - ' . $order->kode_order)
@section('page-title', 'Konfirmasi Pembayaran')
@section('page-subtitle', 'Instruksi Transfer & Unggah Bukti Pembayaran Pesanan ' . $order->kode_order)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Status Banner -->
    <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400">Kode Pesanan:</span>
            <h3 class="text-xl font-mono font-black text-gray-900 dark:text-white">{{ $order->kode_order }}</h3>
            <p class="text-xs text-gray-500 mt-0.5">Produk: <strong>{{ $order->jenis_produk }}</strong> ({{ $order->jumlah }} pcs)</p>
        </div>

        <div class="text-right">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Total Tagihan:</span>
            <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
            </span>
            <span class="block mt-1 px-3 py-0.5 rounded-full text-[11px] font-bold {{ $order->status_color }}">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <!-- Informasi Rekening Bank IKM -->
    <div class="glass p-6 rounded-3xl border border-teal-500/30 bg-teal-500/5 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
            <i class="fas fa-building-columns text-teal-600"></i> Rekening Resmi Sentra IKM Marmer
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700">
                <span class="font-bold text-gray-400 uppercase text-[10px] block">Bank Central Asia (BCA)</span>
                <span class="text-base font-mono font-bold text-gray-900 dark:text-white block mt-1">822-091-8821</span>
                <span class="text-gray-500 mt-0.5 block">a.n. UD Cahaya Onix Tulungagung</span>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700">
                <span class="font-bold text-gray-400 uppercase text-[10px] block">Bank Mandiri</span>
                <span class="text-base font-mono font-bold text-gray-900 dark:text-white block mt-1">144-00-1928-3321</span>
                <span class="text-gray-500 mt-0.5 block">a.n. UD Putra Abadi Tulungagung</span>
            </div>
        </div>

        <p class="text-[11px] text-gray-500 dark:text-gray-400">
            * Silakan transfer nominal tepat sebesar <strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>, lalu unggah foto struk/tangkapan layar m-banking di bawah ini.
        </p>
    </div>

    <!-- Form Unggah Bukti Pembayaran -->
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm" x-data="{ proofPreview: '{{ $order->bukti_pembayaran ? asset('storage/' . $order->bukti_pembayaran) : '' }}' }">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
            <i class="fas fa-receipt text-teal-500"></i> Unggah Bukti Pembayaran
        </h3>
        <p class="text-xs text-gray-400 mb-6">
            Status pesanan akan otomatis berubah menjadi <strong>"Menunggu Verifikasi Pembayaran"</strong> setelah bukti transfer dikirim.
        </p>

        @if($order->bukti_pembayaran)
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-500/30 text-xs text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500 text-base"></i>
                <span>Bukti pembayaran sudah diunggah pada {{ $order->tanggal_pembayaran?->format('d M Y H:i') }} WIB. Anda dapat mengunggah ulang jika ingin mengganti bukti.</span>
            </div>
        @endif

        <form action="{{ route('pembayaran.submitProof', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Upload Box -->
            <div class="border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl p-6 text-center hover:border-teal-500 transition-colors bg-gray-50/50 dark:bg-slate-800/30">
                <input type="file" name="bukti_pembayaran" id="buktiInput" accept="image/*" class="hidden"
                       @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => proofPreview = e.target.result; reader.readAsDataURL(file); }" required>

                <label for="buktiInput" class="cursor-pointer block">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-cloud-arrow-up text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-white block">Klik untuk Memilih File Bukti Transfer</span>
                    <span class="text-[10px] text-gray-400 mt-1 block">Format: JPG, PNG, WEBP (Maksimal 10 MB)</span>
                </label>
            </div>

            <!-- Preview Bukti -->
            <div x-show="proofPreview" class="space-y-2">
                <span class="text-[10px] uppercase font-bold text-gray-400 block">Pratinjau Bukti Transfer:</span>
                <div class="h-64 max-w-sm rounded-2xl border border-gray-200 dark:border-slate-700 overflow-hidden bg-slate-900 flex items-center justify-center">
                    <img :src="proofPreview" alt="Bukti Pembayaran" class="w-full h-full object-contain">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Catatan Pengirim (Opsional)</label>
                <input type="text" name="catatan_bayar" placeholder="Contoh: Transfer dari Rekening BCA a.n. Maya Lestari" class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('tracking.index', ['kode_order' => $order->kode_order]) }}" class="text-xs text-teal-600 dark:text-teal-400 font-semibold hover:underline">
                    <i class="fas fa-location-crosshairs mr-1"></i> Pantau Progres di WIP Tracker
                </a>

                <button type="submit" class="px-7 py-3 rounded-2xl bg-teal-600 hover:bg-teal-500 text-white font-extrabold text-xs shadow-md shadow-teal-500/25 transition-all flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
