@extends('layouts.app')

@section('title', 'Edit Data Hilirisasi Limbah')
@section('page-title', 'Edit Pemanfaatan Limbah')
@section('page-subtitle', 'Perbarui Informasi Penjualan Brongkolan & Pemanfaatan Sampingan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pemanfaatan-limbah.update', $wasteUtilization) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Sentra IKM Penghasil</label>
                    <select name="ikm_profile_id" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        @foreach($ikmProfiles as $ikm)
                            <option value="{{ $ikm->id }}" {{ $wasteUtilization->ikm_profile_id == $ikm->id ? 'selected' : '' }}>
                                {{ $ikm->nama_usaha }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Jenis Sisa / Limbah</label>
                    <input type="text" name="jenis_limbah" value="{{ old('jenis_limbah', $wasteUtilization->jenis_limbah) }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Estimasi Volume / Jumlah</label>
                    <input type="text" name="volume_estimasi" value="{{ old('volume_estimasi', $wasteUtilization->volume_estimasi) }}" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Bentuk Pemanfaatan / Hilirisasi</label>
                    <input type="text" name="pemanfaatan" value="{{ old('pemanfaatan', $wasteUtilization->pemanfaatan) }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Status Penjualan / Hilirisasi</label>
                    <select name="status" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        <option value="tersedia" {{ $wasteUtilization->status == 'tersedia' ? 'selected' : '' }}>Tersedia untuk Pengrajin Lain</option>
                        <option value="terjual" {{ $wasteUtilization->status == 'terjual' ? 'selected' : '' }}>Sudah Terjual</option>
                        <option value="dimanfaatkan" {{ $wasteUtilization->status == 'dimanfaatkan' ? 'selected' : '' }}>Dimanfaatkan Sendiri (Internal)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nilai Jual Sampingan (Rp)</label>
                    <input type="number" name="harga_jual" value="{{ old('harga_jual', (int)$wasteUtilization->harga_jual) }}" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Pembeli / Mitra Penerima Limbah</label>
                    <input type="text" name="pembeli" value="{{ old('pembeli', $wasteUtilization->pembeli) }}" placeholder="Nama pengrajin mozaik, kontaktor urukan, atau pihak ketiga..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Catatan Tambahan</label>
                    <textarea name="catatan" rows="3" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">{{ old('catatan', $wasteUtilization->catatan) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
                <a href="{{ route('pemanfaatan-limbah.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
