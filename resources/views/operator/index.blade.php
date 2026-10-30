@extends('layouts.app')

@section('title', 'Mode Operator Bengkel (Shopfloor Touch)')
@section('page-title', 'Shopfloor Operator Bengkel')
@section('page-subtitle', 'Antarmuka Cepat Stasiun Produksi untuk HP & Tablet di Area Kerja')

@section('content')
<div class="space-y-6">

    <!-- IKM Switcher & Station Filter -->
    <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            @foreach($ikmProfiles as $ikm)
                <a href="{{ route('operator.index', ['ikm' => $ikm->id]) }}" class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $selectedIkm->id == $ikm->id ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300' }}">
                    <i class="fas fa-hammer mr-1.5"></i> {{ $ikm->nama_usaha }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-2 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
            <span class="text-gray-500 dark:text-gray-400">Total Antrean Aktif: <strong class="text-amber-500 font-bold">{{ $activeOrders->count() }} Order</strong></span>
        </div>
    </div>

    <!-- Active Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($activeOrders as $order)
            <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-gray-200">
                            {{ $order->kode_order }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ $order->jenis_produk }}</h3>
                    <p class="text-xs text-teal-600 dark:text-teal-400 font-bold mt-0.5">Jumlah: {{ $order->jumlah }} Unit &bull; Pelanggan: {{ $order->nama_pelanggan }}</p>

                    <!-- Spesifikasi Teknis Pemotongan / Pembubutan -->
                    <div class="mt-4 p-3.5 rounded-2xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-800 text-xs">
                        <span class="text-[10px] uppercase font-bold text-gray-400 block mb-1">
                            <i class="fas fa-ruler-combined text-teal-500 mr-1"></i> Catatan Spesifikasi Kerja
                        </span>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $order->spesifikasi ?? 'Ukuran standar model mangkok wastafel.' }}</p>
                    </div>
                </div>

                <!-- One-Click Fast Advance Buttons -->
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-2">
                        Pindahkan ke Stasiun Kerja Selanjutnya:
                    </span>

                    <form action="{{ route('operator.quickAdvance', $order) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @php
                                $nextOptions = [
                                    'cutting' => ['label' => 'Potong Slep', 'icon' => 'fa-scissors'],
                                    'bubut_gerinda' => ['label' => 'Bubut/Gerinda', 'icon' => 'fa-gear'],
                                    'resin_polishing' => ['label' => 'Resin/Ampelas', 'icon' => 'fa-layer-group'],
                                    'qc_pertama' => ['label' => 'QC 1 (Retak)', 'icon' => 'fa-clipboard-check'],
                                    'finishing' => ['label' => 'Poles Hi-Gloss', 'icon' => 'fa-wand-magic-sparkles'],
                                    'qc_kedua' => ['label' => 'QC 2 (Afur)', 'icon' => 'fa-check-double'],
                                    'packing' => ['label' => 'Packing', 'icon' => 'fa-box'],
                                    'ready_to_ship' => ['label' => 'Siap Ekspedisi', 'icon' => 'fa-truck-fast'],
                                ];
                            @endphp

                            @foreach($nextOptions as $val => $opt)
                                <button type="submit" name="status" value="{{ $val }}"
                                        class="px-2.5 py-2.5 rounded-xl text-xs font-semibold border flex items-center justify-center gap-1.5 transition-all
                                        {{ $order->status == $val ? 'bg-teal-600 text-white border-teal-600 shadow-sm' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-slate-700 hover:border-teal-500' }}">
                                    <i class="fas {{ $opt['icon'] }} text-[10px]"></i>
                                    <span>{{ $opt['label'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-2 mt-2">
                            <input type="text" name="operator" placeholder="Nama Operator / Mesin (cth: Pak Joko - Bubut 2)" class="w-full px-3 py-1.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200">
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-16 glass rounded-3xl border border-gray-200/70 dark:border-slate-800">
                <i class="fas fa-circle-check text-emerald-500 text-4xl mb-3"></i>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Tidak Ada Antrean Produksi</h3>
                <p class="text-xs text-gray-500 mt-1">Semua pesanan di sentra ini sudah terkirim atau belum ada order masuk.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
