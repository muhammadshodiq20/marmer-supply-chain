@extends('layouts.app')

@section('title', 'System Requirements - Transformasi Digital IKM')
@section('page-title', 'Matriks Kebutuhan Sistem (System Requirements)')
@section('page-subtitle', 'Pemetaan Masalah Operasional Lapangan & Solusi Digital E-Supply Chain Marmer')

@section('content')
<div class="space-y-6">

    <!-- 20 Temuan Wawancara & Solusi Sistem -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-list-check text-teal-500"></i> Matriks 20 Masalah Lapangan & Fitur Penyelesainya
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Berdasarkan hasil observasi lapangan IKM Marmer Campurdarat, Tulungagung</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-teal-500/10 text-teal-600 dark:text-teal-400">
                100% Terakomodasi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                        <th class="py-3 px-6 font-semibold">No</th>
                        <th class="py-3 px-6 font-semibold">Mitra IKM</th>
                        <th class="py-3 px-6 font-semibold">Aktivitas Bengkel</th>
                        <th class="py-3 px-6 font-semibold">Alat/Sistem Lama</th>
                        <th class="py-3 px-6 font-semibold">Masalah & Kendala Operasional</th>
                        <th class="py-3 px-6 font-semibold">Solusi Fitur E-Supply Chain</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300 text-xs">
                    @forelse($systemNeeds as $idx => $need)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-6 font-bold text-gray-400">{{ $idx + 1 }}</td>
                            <td class="py-3 px-6 font-semibold text-teal-600 dark:text-teal-400">
                                {{ $need->ikmProfile->nama_usaha }}
                            </td>
                            <td class="py-3 px-6 font-bold text-gray-900 dark:text-white">
                                {{ $need->aktivitas }}
                            </td>
                            <td class="py-3 px-6 text-gray-500">
                                {{ $need->alat_sistem }}
                            </td>
                            <td class="py-3 px-6 text-rose-600 dark:text-rose-400 max-w-xs">
                                {{ $need->masalah_kendala }}
                            </td>
                            <td class="py-3 px-6 font-semibold text-emerald-600 dark:text-emerald-400 max-w-xs">
                                @if(str_contains(strtolower($need->aktivitas), 'stok') || str_contains(strtolower($need->masalah_kendala), 'buku'))
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-check-circle"></i> Modul Digital Bahan Baku & Dimensi Balok</span>
                                @elseif(str_contains(strtolower($need->aktivitas), 'order') || str_contains(strtolower($need->masalah_kendala), 'wa'))
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-check-circle"></i> Katalog HD & Pemesanan Mandiri Buyer</span>
                                @elseif(str_contains(strtolower($need->aktivitas), 'limbah') || str_contains(strtolower($need->masalah_kendala), 'brongkolan'))
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-check-circle"></i> Modul Hilirisasi Circular Economy</span>
                                @else
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-check-circle"></i> Pelacakan WIP & Shopfloor Operator Touch</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Belum ada data matriks kebutuhan sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
