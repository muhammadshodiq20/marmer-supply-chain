@extends('layouts.app')

@section('title', 'Circular Supply Chain & Hilirisasi Limbah')
@section('page-title', 'Circular Supply Chain (Pemanfaatan Limbah)')
@section('page-subtitle', 'Monetisasi & Hilirisasi Brongkolan Mozaik, Wall Cladding, & Pasir Marmer')

@section('content')
<div class="space-y-6">

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Valuasi Penjualan Limbah</span>
            <h3 class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-2">
                Rp {{ number_format($totalValuation, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">Total revenue sampingan dari brongkolan & urukan</p>
        </div>

        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Batch Hilirisasi</span>
            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $itemCount }} Catatan</h3>
            <p class="text-xs text-teal-600 dark:text-teal-400 mt-1">Mozaik, Tempat Sabun Mini, Wall Cladding</p>
        </div>

        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase">Prinsip Rantai Pasok</span>
                <h3 class="text-xl font-bold text-teal-600 dark:text-teal-400 mt-1">Zero Waste IKM</h3>
                <p class="text-xs text-gray-500 mt-0.5">Meminimalkan limbah terbuang ke lingkungan</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xl">
                <i class="fas fa-arrows-spin"></i>
            </div>
        </div>
    </div>

    <!-- Table Pemanfaatan Limbah with Full CRUD -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-leaf text-teal-500"></i> Katalog Penjualan & Pemanfaatan Limbah Sampingan
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Pengelolaan lengkap: Tambah, Lihat Detail, Edit, dan Hapus data hilirisasi</p>
            </div>
            
            @if(auth()->user()->isAdmin())
                <a href="{{ route('pemanfaatan-limbah.create') }}" class="px-4 py-2.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 flex items-center gap-1.5 transition-all">
                    <i class="fas fa-plus"></i> Tambah Data Limbah
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                        <th class="py-3 px-6 font-semibold">IKM Penghasil</th>
                        <th class="py-3 px-6 font-semibold">Jenis Sisa / Limbah</th>
                        <th class="py-3 px-6 font-semibold">Volume Estimasi</th>
                        <th class="py-3 px-6 font-semibold">Bentuk Hilirisasi</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                        <th class="py-3 px-6 font-semibold">Nilai Jual</th>
                        <th class="py-3 px-6 font-semibold">Pembeli</th>
                        <th class="py-3 px-6 font-semibold text-center">Aksi (CRUD)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300">
                    @forelse($utilizations as $u)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6 font-semibold text-teal-600 dark:text-teal-400">
                                {{ $u->ikmProfile->nama_usaha }}
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                {{ $u->jenis_limbah }}
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500">
                                {{ $u->volume_estimasi ?? '-' }}
                            </td>
                            <td class="py-4 px-6 font-medium">
                                <span class="px-2.5 py-1 rounded-lg bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300 text-xs font-semibold">
                                    {{ $u->pemanfaatan }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $u->status == 'terjual' ? 'bg-emerald-100 text-emerald-700' : ($u->status == 'dimanfaatkan' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-extrabold text-gray-900 dark:text-white">
                                {{ $u->harga_jual ? 'Rp ' . number_format($u->harga_jual, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-600 dark:text-gray-400">
                                {{ $u->pembeli ?? 'Tersedia untuk Pengrajin Lain' }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Lihat -->
                                    <a href="{{ route('pemanfaatan-limbah.show', $u) }}" title="Lihat Detail" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 hover:bg-teal-500 hover:text-white text-gray-600 dark:text-gray-300 text-xs transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if(auth()->user()->isAdmin())
                                        <!-- Edit -->
                                        <a href="{{ route('pemanfaatan-limbah.edit', $u) }}" title="Edit Data" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 hover:bg-amber-500 hover:text-white text-gray-600 dark:text-gray-300 text-xs transition-colors">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>

                                        <!-- Hapus -->
                                        <form action="{{ route('pemanfaatan-limbah.destroy', $u) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data limbah ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Data" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 hover:bg-rose-500 hover:text-white text-gray-600 dark:text-gray-300 text-xs transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Belum ada pencatatan pemanfaatan limbah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
