@extends('layouts.app')

@section('title', 'Manajemen Bahan Baku & Inventory')
@section('page-title', 'Bahan Baku & Inventory')
@section('page-subtitle', 'Pencatatan Bongkahan Marmer Besole, TR, & Batu Kali Tulungagung')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter -->
    <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('bahan-baku.index') }}" class="flex flex-wrap items-center gap-3">
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

            <button type="submit" class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            @if(request()->anyFilled(['ikm', 'bulan']))
                <a href="{{ route('bahan-baku.index') }}" class="px-3 py-2 text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400">Reset</a>
            @endif
        </form>

        <a href="{{ route('bahan-baku.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 transition-all self-start md:self-auto">
            <i class="fas fa-plus"></i> Tambah Bahan Masuk
        </a>
    </div>

    <!-- Table Card -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fas fa-cubes text-teal-500"></i> Riwayat Penerimaan Bahan Baku (2026)
            </h3>
            <span class="text-xs px-2.5 py-1 rounded-full bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-semibold border border-teal-500/20">
                {{ $rawMaterials->total() }} Data Tercatat
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                        <th class="py-3 px-6 font-semibold">Tahun / Bulan</th>
                        <th class="py-3 px-6 font-semibold">IKM Penerima</th>
                        <th class="py-3 px-6 font-semibold">Jenis Bahan / Batu</th>
                        <th class="py-3 px-6 font-semibold">Volume / Jumlah</th>
                        <th class="py-3 px-6 font-semibold">Spesifikasi Dimensi</th>
                        <th class="py-3 px-6 font-semibold">Warna / Motif</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300">
                    @forelse($rawMaterials as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                {{ $item->bulan }} {{ $item->tahun }}
                            </td>
                            <td class="py-4 px-6 font-medium text-teal-600 dark:text-teal-400">
                                {{ $item->ikmProfile->nama_usaha }}
                            </td>
                            <td class="py-4 px-6 font-semibold">
                                {{ $item->jenis_batu }}
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-slate-800 text-xs">
                                    {{ $item->jumlah_pembelian }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500">
                                {{ $item->dimensi ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <span class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-300">
                                    <span class="w-2 h-2 rounded-full bg-teal-400"></span> {{ $item->warna ?? 'Alami' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <form action="{{ route('bahan-baku.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs p-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Tidak ada data bahan baku ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-slate-800">
            {{ $rawMaterials->links() }}
        </div>
    </div>

</div>
@endsection
