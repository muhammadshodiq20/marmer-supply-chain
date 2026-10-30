@extends('layouts.app')

@section('title', 'Profil Sentra IKM Tulungagung')
@section('page-title', 'Profil Mitra Sentra IKM')
@section('page-subtitle', 'Informasi Detail Kapasitas Usaha, Pengrajin, & Karakteristik Wilayah')

@section('content')
<div class="space-y-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($ikmProfiles as $ikm)
            <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3.5 py-1.5 rounded-full bg-teal-500/10 text-teal-600 dark:text-teal-400 text-xs font-bold border border-teal-500/20">
                            {{ $ikm->fokus_produksi }}
                        </span>
                        <span class="text-xs text-gray-500"><i class="fas fa-location-dot text-rose-500 mr-1"></i> Tulungagung</span>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $ikm->nama_usaha }}</h3>
                    <p class="text-xs font-semibold text-teal-600 dark:text-teal-400 mt-1">
                        Pimpinan / Pemilik: <strong class="text-gray-800 dark:text-gray-200">{{ $ikm->nama_pemilik }}</strong>
                    </p>

                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-4 leading-relaxed">
                        {{ $ikm->deskripsi }}
                    </p>

                    <div class="mt-6 p-4 rounded-2xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-800 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Kapasitas Produksi:</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $ikm->kekuatan_produksi }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Lokasi Bengkel:</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $ikm->lokasi }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Kontak WhatsApp:</span>
                            <span class="font-mono text-teal-600 font-bold">{{ $ikm->telepon ?? '0812-xxxx-xxxx' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span><strong>{{ $ikm->raw_materials_count }}</strong> Bahan</span>
                        <span><strong>{{ $ikm->orders_count }}</strong> Order</span>
                    </div>
                    <a href="{{ route('alur-proses.index', ['ikm' => $ikm->id]) }}" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition-all shadow-sm">
                        Alur VSM & Waste <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
