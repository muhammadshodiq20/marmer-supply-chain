@extends('layouts.app')

@section('title', 'Alur Proses & Pemetaan Nilai (VSM)')
@section('page-title', 'Pemetaan Alur Proses Bisnis & Analisis Waste')
@section('page-subtitle', 'Analisis Value Stream, Gap Operasional, & Identifikasi Pemborosan IKM')

@section('content')
<div class="space-y-8">

    <!-- IKM Switcher -->
    <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            @foreach($ikmProfiles as $ikm)
                <a href="{{ route('alur-proses.index', ['ikm' => $ikm->id]) }}" class="px-5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $selectedIkm->id == $ikm->id ? 'bg-teal-600 text-white shadow-md shadow-teal-500/20' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                    <i class="fas fa-building mr-1.5"></i> {{ $ikm->nama_usaha }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-2 text-xs text-gray-500">
            <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
            Fokus: <strong class="text-gray-900 dark:text-white">{{ $selectedIkm->fokus_produksi }}</strong>
        </div>
    </div>

    <!-- Overview Banner VSM -->
    <div class="glass p-6 rounded-3xl border border-teal-500/30 bg-teal-500/5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-widest">Supply Chain & Lean Diagnostic</span>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $selectedIkm->nama_usaha }} - Pemetaan Tahapan Kerja</h3>
                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                    Kapasitas Terpasang: <strong class="text-teal-600">{{ $selectedIkm->kekuatan_produksi }}</strong> &bull; Lokasi: {{ $selectedIkm->lokasi }}
                </p>
            </div>
            <span class="text-xs px-3.5 py-1.5 rounded-xl bg-teal-600 text-white font-bold self-start md:self-auto shadow-sm">
                {{ $processFlows->count() }} Tahapan Terpetakan
            </span>
        </div>
    </div>

    <!-- Process Flow Cards -->
    <div class="space-y-4">
        @forelse($processFlows as $step)
            <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm card-hover relative overflow-hidden">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    <!-- Left: Step number and Title -->
                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-10 h-10 rounded-2xl bg-teal-500 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-md">
                            {{ $step->nomor_urut }}
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase tracking-wider">
                                    {{ $step->jenis_produk_konteks ?? 'Pengolahan' }}
                                </span>
                                <h4 class="text-base font-bold text-gray-900 dark:text-white mt-0.5">
                                    {{ $step->tahap_proses }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                    <i class="fas fa-users-gear text-teal-500"></i> Aktor: <strong class="text-gray-700 dark:text-gray-300">{{ $step->aktor_terlibat }}</strong>
                                </p>
                            </div>

                            <!-- Grid 2 Kolom: Kondisi vs Gap -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                <div class="p-3.5 rounded-2xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-800">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">
                                        <i class="fas fa-clipboard-list text-cyan-500"></i> Kondisi Riil Saat Ini
                                    </span>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">{{ $step->kondisi_saat_ini }}</p>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-amber-500/5 border border-amber-500/20">
                                    <span class="text-[10px] font-bold text-amber-500 uppercase block mb-1">
                                        <i class="fas fa-triangle-exclamation text-amber-500"></i> Gap Teridentifikasi
                                    </span>
                                    <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed">{{ $step->gap_teridentifikasi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Waste Tag & Dampak -->
                    <div class="lg:w-80 shrink-0 space-y-3 pt-4 lg:pt-0 lg:border-l lg:border-gray-100 lg:dark:border-slate-800 lg:pl-6">
                        <div>
                            <span class="text-[10px] text-gray-400 uppercase font-semibold block mb-1.5">Kategori Waste Teramati</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 text-xs font-bold border border-rose-500/20">
                                <i class="fas fa-fire-flame-curved"></i> {{ $step->jenis_waste }}
                            </span>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-900 text-slate-200 text-xs leading-relaxed border border-slate-800">
                            <span class="text-[10px] font-bold text-teal-400 uppercase block mb-1">Dampak (Waktu/Biaya/Kualitas)</span>
                            {{ $step->dampak }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center py-12 text-gray-400">Belum ada pemetaan alur proses.</p>
        @endforelse
    </div>

</div>
@endsection
