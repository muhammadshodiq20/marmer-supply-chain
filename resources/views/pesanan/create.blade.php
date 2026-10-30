@extends('layouts.app')

@section('title', 'Buat Pesanan Baru (Make-to-Order)')
@section('page-title', 'Buat Pesanan Baru')
@section('page-subtitle', 'Penerimaan Pesanan Pelanggan Baru ke Jalur WIP')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
        <form action="{{ route('pesanan.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Pilih IKM Mitra</label>
                    <select name="ikm_profile_id" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        @foreach($ikmProfiles as $ikm)
                            <option value="{{ $ikm->id }}">{{ $ikm->nama_usaha }} ({{ $ikm->fokus_produksi }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Tanggal Pesanan</label>
                    <input type="date" name="tanggal_order" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nama Pelanggan / Buyer</label>
                    <input type="text" name="nama_pelanggan" placeholder="Nama perseorangan atau perusahaan..." required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">No. WhatsApp / Telepon</label>
                    <input type="text" name="telepon_pelanggan" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Jenis Produk</label>
                    <input type="text" name="jenis_produk" placeholder="Contoh: Wastafel Marmer Putih / Stepping Batu Kali" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Jumlah (Qty)</label>
                    <input type="number" name="jumlah" value="1" min="1" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Alamat Pengiriman (Tujuan)</label>
                <input type="text" name="alamat_pengiriman" placeholder="Kota/Kabupaten tujuan pengiriman (ongkir ditanggung pembeli)..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Spesifikasi Kustom Pembeli</label>
                <textarea name="spesifikasi" rows="3" placeholder="Permintaan spesifik warna motif (marmer polos vs urat alami), diameter, lubang afur..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-800">
                <a href="{{ route('pesanan.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20">Simpan & Mulai WIP</button>
            </div>
        </form>
    </div>
</div>
@endsection
