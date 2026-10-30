@extends('layouts.app')

@section('title', 'Edit Pesanan ' . $order->kode_order)
@section('page-title', 'Edit Data Pesanan')
@section('page-subtitle', 'Koreksi Data Pelanggan, Spesifikasi, & Status')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
        <form action="{{ route('pesanan.update', $order) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Sentra IKM</label>
                    <select name="ikm_profile_id" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        @foreach($ikmProfiles as $ikm)
                            <option value="{{ $ikm->id }}" {{ $order->ikm_profile_id == $ikm->id ? 'selected' : '' }}>{{ $ikm->nama_usaha }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Tanggal Pesanan</label>
                    <input type="date" name="tanggal_order" value="{{ $order->tanggal_order->format('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" value="{{ $order->nama_pelanggan }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Telepon / WhatsApp</label>
                    <input type="text" name="telepon_pelanggan" value="{{ $order->telepon_pelanggan }}" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Jenis Produk</label>
                    <input type="text" name="jenis_produk" value="{{ $order->jenis_produk }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Jumlah Qty</label>
                    <input type="number" name="jumlah" value="{{ $order->jumlah }}" min="1" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Alamat Tujuan Pengiriman</label>
                <input type="text" name="alamat_pengiriman" value="{{ $order->alamat_pengiriman }}" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Spesifikasi Permintaan Pelanggan</label>
                <textarea name="spesifikasi" rows="3" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">{{ $order->spesifikasi }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Status WIP</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500 font-semibold">
                    @foreach(\App\Models\Order::STATUS_LABELS as $key => $lbl)
                        <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-800">
                <a href="{{ route('pesanan.show', $order) }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
