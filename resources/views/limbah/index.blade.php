@extends('layouts.app')

@section('title', 'Observasi Pemborosan (Waste Analysis)')
@section('page-title', 'Manajemen & Observasi Pemborosan')
@section('page-subtitle', 'Pencatatan 7 Waste (Motion, Waiting, Overprocessing, Rework, Defect, Transportation, Inventory)')

@section('content')
<div class="space-y-6">

    <!-- Top Summary Pills -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
        @php
            $wasteTypes = ['Motion', 'Overprocessing', 'Defect', 'Transportation', 'Waiting', 'Rework', 'Inventory'];
        @endphp
        @foreach($wasteTypes as $wt)
            <div class="glass p-3 rounded-2xl border border-gray-200/70 dark:border-slate-800 text-center">
                <span class="text-[10px] text-gray-500 uppercase font-semibold block">{{ $wt }}</span>
                <span class="text-xs font-bold text-teal-600 dark:text-teal-400 mt-1 inline-block">Teridentifikasi</span>
            </div>
        @endforeach
    </div>

    <!-- Filter Bar -->
    <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('limbah.index') }}" class="flex flex-wrap items-center gap-3">
            <select name="ikm" class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua IKM</option>
                @foreach($ikmProfiles as $ikm)
                    <option value="{{ $ikm->id }}" {{ request('ikm') == $ikm->id ? 'selected' : '' }}>{{ $ikm->nama_usaha }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold shadow-sm">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </form>

        <a href="{{ route('pemanfaatan-limbah.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20">
            <i class="fas fa-recycle"></i> Buka Sirkular Hilirisasi Limbah
        </a>
    </div>

    <!-- Table Observasi Waste -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                        <th class="py-3 px-6 font-semibold">Tgl & Jam</th>
                        <th class="py-3 px-6 font-semibold">Sentra IKM</th>
                        <th class="py-3 px-6 font-semibold">Aktivitas yang Diamati</th>
                        <th class="py-3 px-6 font-semibold">Jenis Pemborosan (Waste)</th>
                        <th class="py-3 px-6 font-semibold">Catatan Lapangan & Rekomendasi Solusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300">
                    @forelse($wasteRecords as $wr)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6 text-xs whitespace-nowrap">
                                <span class="font-bold text-gray-900 dark:text-white block">{{ $wr->tanggal->format('d M Y') }}</span>
                                <span class="text-gray-400 font-mono">{{ $wr->waktu_mulai }} - {{ $wr->waktu_selesai }}</span>
                            </td>
                            <td class="py-4 px-6 font-medium text-teal-600 dark:text-teal-400 whitespace-nowrap">
                                {{ $wr->ikmProfile->nama_usaha }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-gray-900 dark:text-white">
                                {{ $wr->aktivitas_diamati }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                    <i class="fas fa-triangle-exclamation text-[10px]"></i> {{ $wr->jenis_waste }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-600 dark:text-gray-300 leading-relaxed max-w-md">
                                {{ $wr->catatan }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">Belum ada data observasi waste.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-slate-800">
            {{ $wasteRecords->links() }}
        </div>
    </div>

</div>
@endsection
