@extends('layouts.app')

@section('title', 'Rekapitulasi Produksi Bulanan')
@section('page-title', 'Rekapitulasi Produksi')
@section('page-subtitle', 'Pencatatan Volume Output Produk Jadi Periode Januari - Agustus 2026')

@section('content')
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('produksi.index') }}" class="flex flex-wrap items-center gap-3">
            <select name="ikm" class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua IKM</option>
                @foreach($ikmProfiles as $ikm)
                    <option value="{{ $ikm->id }}" {{ request('ikm') == $ikm->id ? 'selected' : '' }}>{{ $ikm->nama_usaha }}</option>
                @endforeach
            </select>

            <select name="bulan" class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua Bulan</option>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus'] as $b)
                    <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold shadow-sm">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            @if(request()->anyFilled(['ikm', 'bulan']))
                <a href="{{ route('produksi.index') }}" class="text-xs text-gray-400">Reset</a>
            @endif
        </form>

        <span class="text-xs font-semibold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/30 px-3 py-1.5 rounded-full border border-teal-500/20">
            Total Rekap: {{ $records->total() }} Batch Produksi
        </span>
    </div>

    <!-- Table Rekap -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                        <th class="py-3 px-6 font-semibold">Tahun / Periode</th>
                        <th class="py-3 px-6 font-semibold">Bulan</th>
                        <th class="py-3 px-6 font-semibold">Sentra IKM</th>
                        <th class="py-3 px-6 font-semibold">Jenis Produk</th>
                        <th class="py-3 px-6 font-semibold text-right">Jumlah Output Produksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300">
                    @forelse($records as $rec)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6 font-mono text-gray-500">
                                {{ $rec->tahun }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-gray-900 dark:text-white">
                                {{ $rec->bulan }}
                            </td>
                            <td class="py-4 px-6 font-medium text-teal-600 dark:text-teal-400">
                                {{ $rec->ikmProfile->nama_usaha }}
                            </td>
                            <td class="py-4 px-6 font-semibold">
                                {{ $rec->jenis_produk }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <span class="px-3 py-1 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300 font-extrabold text-sm border border-teal-500/20">
                                    {{ $rec->jumlah_produksi }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">Tidak ada data rekapitulasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-slate-800">
            {{ $records->links() }}
        </div>
    </div>

</div>
@endsection
