@extends('layouts.app')

@section('title', 'Tambah Hilirisasi Limbah Baru')
@section('page-title', 'Tambah Pemanfaatan Limbah')
@section('page-subtitle', 'Pencatatan Monetisasi Sisa Potongan, Mozaik, & Urukan (Circular Economy)')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
        <form action="{{ route('pemanfaatan-limbah.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Sentra IKM Penghasil</label>
                    <select name="ikm_profile_id" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        @foreach($ikmProfiles as $ikm)
                            <option value="{{ $ikm->id }}">{{ $ikm->nama_usaha }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Jenis Sisa / Limbah</label>
                    <input type="text" name="jenis_limbah" placeholder="Contoh: Brongkolan Marmer Putih / Pasir Bubut / Sisi Batu Kali" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Estimasi Volume / Jumlah</label>
                    <input type="text" name="volume_estimasi" placeholder="Contoh: 2 Ton / 3 Rit Truk / 200 Pcs" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Bentuk Pemanfaatan / Hilirisasi</label>
                    <input type="text" name="pemanfaatan" placeholder="Contoh: Kerajinan Mozaik / Wall Cladding / Urukan Pondasi" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Status Penjualan / Hilirisasi</label>
                    <select name="status" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        <option value="tersedia">Tersedia untuk Pengrajin Lain</option>
                        <option value="terjual">Sudah Terjual</option>
                        <option value="dimanfaatkan">Dimanfaatkan Sendiri (Internal)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nilai Jual Sampingan (Rp)</label>
                    <input type="number" name="harga_jual" placeholder="Contoh: 750000" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nama Pembeli / Pihak Kedua</label>
                <input type="text" name="pembeli" placeholder="Contoh: Pengrajin Mozaik Ngentrong / Kontraktor Proyek" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Catatan Tambahan</label>
                <textarea name="catatan" rows="3" placeholder="Karakteristik sisa potongan..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-800">
                <a href="{{ route('pemanfaatan-limbah.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20">Simpan Pemanfaatan</button>
            </div>
        </form>
    </div>
</div>
@endsection
