@extends('layouts.app')

@section('title', 'Laporan Operasional & Ekspor Data')
@section('page-title', 'Laporan Operasional & Keuangan')
@section('page-subtitle', 'Rekapitulasi Produksi, Penjualan, Stok Bahan Baku, & Ekspor Excel/PDF')

@section('content')
<div class="space-y-6">

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Pendapatan Nyata -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Pendapatan Pesanan Selesai</span>
            <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">
                Rp {{ number_format($pendapatanSelesai, 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-gray-400 mt-1">
                Dihitung dari transaksi yang telah diverifikasi / berstatus terkirim.
            </p>
        </div>

        <!-- Pendapatan Limbah (Circular) -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Revenue Hilirisasi Limbah</span>
            <h3 class="text-2xl font-black text-teal-600 dark:text-teal-400 mt-2">
                Rp {{ number_format($totalPendapatanLimbah, 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-gray-400 mt-1">Penjualan brongkolan mozaik & urukan</p>
        </div>

        <!-- Volume Pesanan -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Pesanan Masuk</span>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-2">
                {{ $totalPesananMasuk }} Pesanan
            </h3>
            <p class="text-[11px] text-teal-600 mt-1 font-semibold">
                {{ $pesananSelesaiCount }} Selesai &bull; {{ $pesananWipCount }} Dalam Proses (WIP)
            </p>
        </div>

        <!-- Bahan Baku -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Batch Bahan Baku Terdata</span>
            <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-2">
                {{ $rawMaterials->count() }} Balok Batu
            </h3>
            <p class="text-[11px] text-gray-400 mt-1">Besole Putih, TR, Merah, & Batu Kali</p>
        </div>
    </div>

    <!-- Filter & Export Controls -->
    <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-center gap-3">
            <select name="ikm_id" class="px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua Mitra IKM</option>
                @foreach($ikmProfiles as $ikm)
                    <option value="{{ $ikm->id }}" {{ $selectedIkm == $ikm->id ? 'selected' : '' }}>{{ $ikm->nama_usaha }}</option>
                @endforeach
            </select>

            <select name="bulan" class="px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua Bulan</option>
                @foreach($bulanList as $b)
                    <option value="{{ $b }}" {{ $selectedBulan == $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>

            <select name="tahun" class="px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="2026" {{ $selectedTahun == '2026' ? 'selected' : '' }}>Tahun 2026</option>
                <option value="2025" {{ $selectedTahun == '2025' ? 'selected' : '' }}>Tahun 2025</option>
            </select>

            <button type="submit" class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-bold shadow-sm">
                <i class="fas fa-filter mr-1"></i> Terapkan Filter
            </button>
            @if(request()->anyFilled(['ikm_id', 'bulan']))
                <a href="{{ route('laporan.index') }}" class="text-xs text-gray-400 hover:underline">Reset</a>
            @endif
        </form>

        <div class="flex items-center gap-2">
            <!-- Export Excel -->
            <a href="{{ route('laporan.excel', ['tipe' => 'semua']) }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-500/20 flex items-center gap-1.5 transition-all">
                <i class="fas fa-file-excel text-sm"></i> Unduh Excel (.csv)
            </a>

            <!-- Export PDF / Cetak Laporan Resmi -->
            <a href="{{ route('laporan.pdf') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20 flex items-center gap-1.5 transition-all">
                <i class="fas fa-file-pdf text-sm"></i> Cetak / PDF Resmi
            </a>
        </div>
    </div>

    <!-- Preview Tabel Transaksi & Pesanan -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fas fa-receipt text-teal-500"></i> Rekapitulasi Pesanan & Transaksi Penjualan
            </h3>
            <span class="text-xs font-semibold text-gray-400">Total: {{ $orders->count() }} Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                        <th class="py-3 px-6 font-semibold">Kode Order</th>
                        <th class="py-3 px-6 font-semibold">Sentra IKM</th>
                        <th class="py-3 px-6 font-semibold">Pelanggan & Kontak</th>
                        <th class="py-3 px-6 font-semibold">Produk & Vol</th>
                        <th class="py-3 px-6 font-semibold">Total Nilai</th>
                        <th class="py-3 px-6 font-semibold">Status Produksi</th>
                        <th class="py-3 px-6 font-semibold">Status Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300">
                    @forelse($orders as $o)
                        @php
                            $nilai = $o->total_harga > 0 ? $o->total_harga : ($o->jumlah * 450000);
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-teal-600 dark:text-teal-400">
                                {{ $o->kode_order }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-gray-800 dark:text-gray-200">
                                {{ $o->ikmProfile->nama_usaha }}
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <p class="font-bold text-gray-900 dark:text-white">{{ $o->nama_pelanggan }}</p>
                                <p class="text-gray-400">{{ $o->telepon_pelanggan }}</p>
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <span class="font-bold text-gray-800 dark:text-gray-200">{{ $o->jenis_produk }}</span>
                                <span class="block text-gray-400">{{ $o->jumlah }} Unit</span>
                            </td>
                            <td class="py-4 px-6 font-black text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($nilai, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $o->status_color }}">
                                    {{ $o->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ in_array($o->status, ['shipped', 'ready_to_ship']) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ in_array($o->status, ['shipped', 'ready_to_ship']) ? 'Lunas / Terbayar' : 'Menunggu / DP' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Tidak ada data pesanan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
