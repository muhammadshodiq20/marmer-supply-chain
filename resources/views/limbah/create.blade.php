@extends('layouts.app')

@section('title', 'Catat Observasi Waste Baru')
@section('page-title', 'Catat Observasi Waste')
@section('page-subtitle', 'Pencatatan Pemborosan Produksi (Lean Manufacturing Diagnostic)')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
        <form action="{{ route('limbah.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Sentra IKM</label>
                    <select name="ikm_profile_id" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        @foreach($ikmProfiles as $ikm)
                            <option value="{{ $ikm->id }}">{{ $ikm->nama_usaha }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Tanggal Pengamatan</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Waktu Mulai Pengamatan</label>
                    <input type="text" name="waktu_mulai" placeholder="Contoh: 08:00" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Waktu Selesai</label>
                    <input type="text" name="waktu_selesai" placeholder="Contoh: 09:30" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Aktivitas yang Diamati</label>
                    <input type="text" name="aktivitas_diamati" placeholder="Contoh: Pemotongan bongkahan menggunakan mesin slep..." required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Kategori Pemborosan (Waste Type)</label>
                    <select name="jenis_waste" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        <option value="Motion">Motion (Gerakan berlebih operator/handling manual)</option>
                        <option value="Waiting">Waiting (Menunggu bahan/mesin/penyesuaian)</option>
                        <option value="Overprocessing">Overprocessing (Pemolesan/pembelahan berulang)</option>
                        <option value="Defect & Rework">Defect & Rework (Retak/cacat batu harus ditambal resin)</option>
                        <option value="Material Waste">Material Waste (Lumpur slep & potongan kecil)</option>
                        <option value="Transportation">Transportation (Pemindahan fisik antar stasiun)</option>
                        <option value="Inventory">Inventory (Penumpukan bahan/limbah belum terolah)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Catatan Detail Kondisi Lapangan & Solusi</label>
                <textarea name="catatan" rows="3" placeholder="Deskripsikan temuan di lapangan..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-800">
                <a href="{{ route('limbah.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20">Simpan Observasi</button>
            </div>
        </form>
    </div>
</div>
@endsection
