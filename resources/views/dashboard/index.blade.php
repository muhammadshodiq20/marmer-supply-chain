@extends('layouts.app')

@section('title', 'Dashboard Operasional E-Supply Chain')
@section('page-title', 'Dashboard Operasional')
@section('page-subtitle', 'Monitoring Rantai Pasok IKM Marmer & Batu Alam Tulungagung')

@section('content')
<div class="space-y-8">

    <!-- KPI / Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Bahan Baku -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm card-hover relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Bahan Masuk</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $totalBahanBaku }} <span class="text-sm font-normal text-gray-500">Pencatatan</span></h3>
                    <p class="text-xs text-teal-600 dark:text-teal-400 font-medium mt-2 flex items-center gap-1">
                        <i class="fas fa-check-circle"></i> Balok Besole, TR & Batu Kali
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="fas fa-cubes"></i>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-400 to-teal-600"></div>
        </div>

        <!-- Card 2: Rekap Produksi -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm card-hover relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rekapitulasi Produksi</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $totalProduk }} <span class="text-sm font-normal text-gray-500">Batch</span></h3>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-2 flex items-center gap-1">
                        <i class="fas fa-arrow-trend-up"></i> Data Januari - Agustus 2026
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="fas fa-industry"></i>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
        </div>

        <!-- Card 3: Pesanan Aktif WIP -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm card-hover relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pesanan Dalam WIP</p>
                    <h3 class="text-3xl font-extrabold text-cyan-600 dark:text-cyan-400 mt-2">{{ $totalPesananAktif }} <span class="text-sm font-normal text-gray-500">Aktif</span></h3>
                    <p class="text-xs text-cyan-600 dark:text-cyan-400 font-medium mt-2 flex items-center gap-1">
                        <i class="fas fa-spinner fa-spin"></i> Make-to-Order Tracking
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-400 to-cyan-600"></div>
        </div>

        <!-- Card 4: Observasi Limbah -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm card-hover relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Observasi Waste</p>
                    <h3 class="text-3xl font-extrabold text-amber-600 dark:text-amber-400 mt-2">{{ $totalLimbah }} <span class="text-sm font-normal text-gray-500">Titik Gap</span></h3>
                    <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mt-2 flex items-center gap-1">
                        <i class="fas fa-recycle"></i> Circular Hilirisasi Siap Jual
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="fas fa-leaf"></i>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-amber-600"></div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Trend Produksi Bulanan (Line/Bar) -->
        <div class="lg:col-span-2 glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-chart-area text-teal-500"></i> Tren Volume Produksi Bulanan 2026
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Komparasi Output UD Cahaya Onix (Marmer) vs UD Putra Abadi (Batu Kali)</p>
                </div>
                <span class="text-xs px-3 py-1.5 rounded-full bg-teal-500/10 text-teal-600 dark:text-teal-400 font-semibold border border-teal-500/20">Data Riil 8 Bulan</span>
            </div>
            <div class="h-80 w-full">
                <canvas id="productionChart"></canvas>
            </div>
        </div>

        <!-- Sebaran Jenis Pemborosan / Waste (Doughnut) -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm flex flex-col">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-pie-chart text-amber-500"></i> Pemetaan Jenis Waste
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Identifikasi 7 Waste di Bengkel Produksi</p>
            </div>
            <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                <canvas id="wasteChart"></canvas>
            </div>
            <p class="text-[11px] text-center text-gray-400 mt-2">Dianalisis dari proses bubut, slep, poles, dan handling manual</p>
        </div>
    </div>

    <!-- Live WIP Tracking Pesanan Terbaru -->
    <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-list-check text-cyan-500"></i> Monitoring Pesanan & Progres WIP Terkini
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tahapan produksi Make-to-Order pelanggan secara real-time</p>
            </div>
            <a href="{{ route('pesanan.index') }}" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1">
                Semua Pesanan <i class="fas fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-slate-800">
                        <th class="pb-3 font-semibold">Kode Order</th>
                        <th class="pb-3 font-semibold">IKM Mitra</th>
                        <th class="pb-3 font-semibold">Pelanggan</th>
                        <th class="pb-3 font-semibold">Produk & Qty</th>
                        <th class="pb-3 font-semibold">Status Tahapan WIP</th>
                        <th class="pb-3 font-semibold">Tgl Order</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 font-mono font-medium text-teal-600 dark:text-teal-400">
                                {{ $order->kode_order }}
                            </td>
                            <td class="py-3.5">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $order->ikmProfile->nama_usaha }}</span>
                            </td>
                            <td class="py-3.5">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->nama_pelanggan }}</p>
                                <p class="text-xs text-gray-400">{{ $order->telepon_pelanggan ?? '-' }}</p>
                            </td>
                            <td class="py-3.5">
                                <span class="font-medium">{{ $order->jenis_produk }}</span>
                                <span class="ml-1 text-xs px-2 py-0.5 rounded-md bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 font-semibold">{{ $order->jumlah }} pcs</span>
                            </td>
                            <td class="py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_color }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 text-xs text-gray-500">
                                {{ $order->tanggal_order->format('d M Y') }}
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('pesanan.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 text-xs font-semibold hover:bg-teal-600 hover:text-white transition-all">
                                    <i class="fas fa-timeline"></i> Lacak WIP
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Belum ada data pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ringkasan Profil IKM -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($ikmProfiles as $ikm)
            <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs px-2.5 py-1 rounded-md bg-teal-500/10 text-teal-600 dark:text-teal-400 font-semibold border border-teal-500/20">
                            {{ $ikm->fokus_produksi }}
                        </span>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-2">{{ $ikm->nama_usaha }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><i class="fas fa-user-circle mr-1"></i> {{ $ikm->nama_pemilik }} &bull; {{ $ikm->lokasi }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-teal-500 text-lg">
                        <i class="fas fa-building"></i>
                    </div>
                </div>

                <p class="text-xs text-gray-600 dark:text-gray-300 mt-4 line-clamp-2">{{ $ikm->deskripsi }}</p>

                <div class="grid grid-cols-3 gap-2 mt-6 pt-4 border-t border-gray-100 dark:border-slate-800 text-center">
                    <div class="p-2 rounded-xl bg-gray-50 dark:bg-slate-800/60">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold">Bahan Baku</p>
                        <p class="text-base font-bold text-gray-800 dark:text-white mt-0.5">{{ $ikm->raw_materials_count }}</p>
                    </div>
                    <div class="p-2 rounded-xl bg-gray-50 dark:bg-slate-800/60">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold">Pesanan</p>
                        <p class="text-base font-bold text-teal-600 dark:text-teal-400 mt-0.5">{{ $ikm->orders_count }}</p>
                    </div>
                    <div class="p-2 rounded-xl bg-gray-50 dark:bg-slate-800/60">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold">Waste</p>
                        <p class="text-base font-bold text-amber-600 dark:text-amber-400 mt-0.5">{{ $ikm->waste_records_count }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Chart Produksi Bulanan
        const prodData = {!! $productionChartData !!};
        const ctxProd = document.getElementById('productionChart').getContext('2d');
        new Chart(ctxProd, {
            type: 'bar',
            data: {
                labels: prodData.labels,
                datasets: prodData.datasets.map(ds => ({
                    ...ds,
                    borderWidth: 2,
                    borderRadius: 8,
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { font: { family: 'Inter', size: 12 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + Number(context.raw).toLocaleString('id-ID') + ' pcs';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(156, 163, 175, 0.1)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Chart Sebaran Waste
        const wasteData = {!! $wasteChartData !!};
        const ctxWaste = document.getElementById('wasteChart').getContext('2d');
        new Chart(ctxWaste, {
            type: 'doughnut',
            data: {
                labels: wasteData.labels,
                datasets: [{
                    data: wasteData.data,
                    backgroundColor: [
                        '#14b8a6', '#f59e0b', '#3b82f6', '#ec4899', '#8b5cf6', '#10b981', '#f97316'
                    ],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { family: 'Inter', size: 11 } }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endpush
