@extends('layouts.app')

@section('title', 'Detail Hilirisasi Limbah')
@section('page-title', 'Detail Pemanfaatan Limbah')
@section('page-subtitle', 'Informasi Hilirisasi & Monetisasi Sisa Potongan Batu')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between pb-6 border-b border-gray-100 dark:border-slate-800">
            <div>
                <span class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-wider">
                    {{ $wasteUtilization->ikmProfile->nama_usaha }}
                </span>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                    {{ $wasteUtilization->jenis_limbah }}
                </h2>
            </div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $wasteUtilization->status == 'terjual' ? 'bg-emerald-100 text-emerald-700' : ($wasteUtilization->status == 'dimanfaatkan' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                {{ ucfirst($wasteUtilization->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 text-sm">
            <div>
                <span class="text-xs text-gray-400 block uppercase font-bold">Bentuk Hilirisasi</span>
                <p class="font-bold text-gray-800 dark:text-gray-200 text-base mt-1">{{ $wasteUtilization->pemanfaatan }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 block uppercase font-bold">Estimasi Volume</span>
                <p class="font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $wasteUtilization->volume_estimasi ?? '-' }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 block uppercase font-bold">Nilai Penjualan</span>
                <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                    {{ $wasteUtilization->harga_jual ? 'Rp ' . number_format($wasteUtilization->harga_jual, 0, ',', '.') : 'Belum Ditetapkan' }}
                </p>
            </div>
            <div>
                <span class="text-xs text-gray-400 block uppercase font-bold">Pembeli / Pihak Kedua</span>
                <p class="font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $wasteUtilization->pembeli ?? 'Tersedia untuk Pengrajin Mozaik' }}</p>
            </div>
        </div>

        @if($wasteUtilization->catatan)
            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-slate-800/40 border border-gray-100 dark:border-slate-800 text-xs">
                <span class="font-bold text-gray-500 uppercase block mb-1">Catatan Tambahan:</span>
                <p class="text-gray-700 dark:text-gray-300">{{ $wasteUtilization->catatan }}</p>
            </div>
        @endif

        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-slate-800 mt-6">
            <a href="{{ route('pemanfaatan-limbah.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
            </a>

            @if(auth()->user()->isAdmin())
                <div class="flex items-center gap-2">
                    <a href="{{ route('pemanfaatan-limbah.edit', $wasteUtilization) }}" class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-bold shadow-sm">
                        <i class="fas fa-pen-to-square mr-1"></i> Edit Data
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
