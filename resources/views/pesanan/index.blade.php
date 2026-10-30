@extends('layouts.app')

@section('title', 'Manajemen Pesanan & WIP Track')
@section('page-title', 'Pesanan & Pelacakan WIP')
@section('page-subtitle', 'Monitoring Alur Work-in-Progress Produksi Marmer & Batu Alam')

@section('content')
<div class="space-y-6">

    <!-- Status Tabs -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
        <a href="{{ route('pesanan.index') }}" class="glass p-3.5 rounded-2xl border border-gray-200/70 dark:border-slate-800 text-center {{ !request('status') ? 'ring-2 ring-teal-500' : '' }}">
            <span class="text-[10px] text-gray-500 uppercase font-semibold">Semua Order</span>
            <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $orders->total() }}</p>
        </a>
        <a href="{{ route('pesanan.index', ['status' => 'order_received']) }}" class="glass p-3.5 rounded-2xl border border-gray-200/70 dark:border-slate-800 text-center {{ request('status') == 'order_received' ? 'ring-2 ring-teal-500' : '' }}">
            <span class="text-[10px] text-gray-500 uppercase font-semibold">Order Received</span>
            <p class="text-xl font-bold text-gray-700 dark:text-gray-300 mt-1">{{ $statusCounts['order_received'] ?? 0 }}</p>
        </a>
        <a href="{{ route('pesanan.index', ['status' => 'cutting']) }}" class="glass p-3.5 rounded-2xl border border-gray-200/70 dark:border-slate-800 text-center {{ request('status') == 'cutting' ? 'ring-2 ring-teal-500' : '' }}">
            <span class="text-[10px] text-blue-500 uppercase font-semibold">Cutting Slep</span>
            <p class="text-xl font-bold text-blue-600 mt-1">{{ $statusCounts['cutting'] ?? 0 }}</p>
        </a>
        <a href="{{ route('pesanan.index', ['status' => 'bubut_gerinda']) }}" class="glass p-3.5 rounded-2xl border border-gray-200/70 dark:border-slate-800 text-center {{ request('status') == 'bubut_gerinda' ? 'ring-2 ring-teal-500' : '' }}">
            <span class="text-[10px] text-amber-500 uppercase font-semibold">Bubut / Gerinda</span>
            <p class="text-xl font-bold text-amber-600 mt-1">{{ $statusCounts['bubut_gerinda'] ?? 0 }}</p>
        </a>
        <a href="{{ route('pesanan.index', ['status' => 'finishing']) }}" class="glass p-3.5 rounded-2xl border border-gray-200/70 dark:border-slate-800 text-center {{ request('status') == 'finishing' ? 'ring-2 ring-teal-500' : '' }}">
            <span class="text-[10px] text-purple-500 uppercase font-semibold">Poles & Finishing</span>
            <p class="text-xl font-bold text-purple-600 mt-1">{{ $statusCounts['finishing'] ?? 0 }}</p>
        </a>
        <a href="{{ route('pesanan.index', ['status' => 'ready_to_ship']) }}" class="glass p-3.5 rounded-2xl border border-gray-200/70 dark:border-slate-800 text-center {{ request('status') == 'ready_to_ship' ? 'ring-2 ring-teal-500' : '' }}">
            <span class="text-[10px] text-emerald-500 uppercase font-semibold">Siap Kirim</span>
            <p class="text-xl font-bold text-emerald-600 mt-1">{{ $statusCounts['ready_to_ship'] ?? 0 }}</p>
        </a>
    </div>

    <!-- Filter & Action Bar -->
    <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('pesanan.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Order / Pelanggan..." class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500 w-64">
            
            <select name="ikm" class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua IKM</option>
                @foreach($ikmProfiles as $ikm)
                    <option value="{{ $ikm->id }}" {{ request('ikm') == $ikm->id ? 'selected' : '' }}>{{ $ikm->nama_usaha }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="fas fa-search mr-1"></i> Cari
            </button>
        </form>

        <a href="{{ route('pesanan.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 transition-all self-start md:self-auto">
            <i class="fas fa-plus"></i> Buat Order Baru
        </a>
    </div>

    <!-- Table Orders -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                        <th class="py-3 px-6 font-semibold">Kode Order</th>
                        <th class="py-3 px-6 font-semibold">IKM Mitra</th>
                        <th class="py-3 px-6 font-semibold">Pelanggan & Tujuan</th>
                        <th class="py-3 px-6 font-semibold">Spesifikasi Pesanan</th>
                        <th class="py-3 px-6 font-semibold">Tahapan WIP</th>
                        <th class="py-3 px-6 font-semibold">Tgl Order</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6 font-mono font-medium text-teal-600 dark:text-teal-400">
                                <a href="{{ route('pesanan.show', $order) }}" class="hover:underline font-bold">
                                    {{ $order->kode_order }}
                                </a>
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                {{ $order->ikmProfile->nama_usaha }}
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $order->nama_pelanggan }}</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5"><i class="fas fa-location-dot text-rose-400"></i> {{ $order->alamat_pengiriman ?? 'Tulungagung' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-bold text-gray-900 dark:text-white">{{ $order->jenis_produk }} <span class="text-xs font-normal text-teal-600">({{ $order->jumlah }} unit)</span></p>
                                <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $order->spesifikasi ?? 'Kustom standar' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_color }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500">
                                {{ $order->tanggal_order->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('pesanan.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 text-xs font-semibold hover:bg-teal-600 hover:text-white transition-all">
                                    <i class="fas fa-eye"></i> Detail WIP
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Tidak ada pesanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-slate-800">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
