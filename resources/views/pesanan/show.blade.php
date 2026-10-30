@extends('layouts.app')

@section('title', 'WIP Track - ' . $order->kode_order)
@section('page-title', 'Pelacakan Produksi (WIP Track)')
@section('page-subtitle', 'Real-time Workflow Pipeline: ' . $order->kode_order)

@section('content')
<div class="space-y-8">

    <!-- Header Information Card -->
    <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3">
                <span class="text-xs px-3 py-1 rounded-full bg-teal-500/10 text-teal-600 dark:text-teal-400 font-bold border border-teal-500/20">
                    {{ $order->ikmProfile->nama_usaha }}
                </span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold {{ $order->tipe_pesanan === 'katalog' ? 'bg-cyan-100 text-cyan-700' : 'bg-purple-100 text-purple-700' }}">
                    {{ $order->tipe_pesanan === 'katalog' ? 'Pembelian Katalog' : 'Pemesanan Kustom' }}
                </span>
                <span class="text-xs text-gray-500 font-mono">{{ $order->kode_order }}</span>
            </div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-white mt-2">{{ $order->jenis_produk }} <span class="text-teal-600 font-bold text-lg">({{ $order->jumlah }} unit)</span></h2>
            <p class="text-xs text-gray-500 mt-1">
                Pemesan: <strong class="text-gray-800 dark:text-gray-200">{{ $order->nama_pelanggan }}</strong> &bull; 
                WhatsApp: {{ $order->telepon_pelanggan ?? '-' }} &bull; 
                Total Nilai: <strong class="text-emerald-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('pesanan.printSpk', $order) }}" target="_blank" class="px-4 py-2 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-sm flex items-center gap-2 border border-slate-700">
                <i class="fas fa-print text-teal-400"></i> Cetak SPK Bengkel
            </a>
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold {{ $order->status_color }} shadow-sm">
                <span class="w-2 h-2 rounded-full bg-current animate-ping"></span>
                Status: {{ $order->status_label }}
            </span>
            <a href="{{ route('pesanan.index') }}" class="px-4 py-2 rounded-2xl border border-gray-200 dark:border-slate-700 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Modul Verifikasi Pembayaran (Jika Ada Bukti Masuk) -->
    @if($order->bukti_pembayaran)
        <div class="glass p-6 rounded-3xl border border-amber-500/30 bg-amber-500/5 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-gray-200 dark:border-slate-700">
                <div>
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block">Verifikasi Bukti Transfer Buyer</span>
                    <h3 class="text-base font-black text-gray-900 dark:text-white mt-0.5">Bukti Pembayaran Masuk</h3>
                    <p class="text-xs text-gray-500">Metode: <strong>{{ strtoupper(str_replace('_', ' ', $order->metode_pembayaran ?? 'Transfer')) }}</strong> &bull; Total: <strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong></p>
                </div>

                <div class="flex items-center gap-2">
                    <form action="{{ route('pesanan.verifyPayment', $order) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-500/20 flex items-center gap-1.5">
                            <i class="fas fa-check-double"></i> Verifikasi Lunas (Approve)
                        </button>
                    </form>

                    <form action="{{ route('pesanan.verifyPayment', $order) }}" method="POST" class="inline" onsubmit="return confirm('Tolak bukti pembayaran ini?')">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-500/20 flex items-center gap-1.5">
                            <i class="fas fa-times"></i> Tolak Bukti
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row items-center gap-4">
                <div class="h-40 w-48 rounded-xl overflow-hidden bg-slate-900 border border-gray-200 dark:border-slate-700 shrink-0">
                    <a href="{{ asset('storage/' . $order->bukti_pembayaran) }}" target="_blank" title="Klik untuk perbesar">
                        <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}" alt="Bukti Transfer" class="w-full h-full object-cover">
                    </a>
                </div>
                <div class="text-xs text-gray-600 dark:text-gray-300 space-y-1">
                    <p>Status Pembayaran Saat Ini: <strong class="uppercase text-amber-600">{{ $order->status_pembayaran }}</strong></p>
                    <p>Waktu Upload: {{ $order->tanggal_pembayaran?->format('d M Y H:i') }} WIB</p>
                    <p class="text-gray-400">Klik gambar struk di samping untuk melihat bukti transfer ukuran penuh di tab baru.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Interactive Pipeline Visual (Horizontal Steps) -->
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-x-auto">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-8 flex items-center gap-2">
            <i class="fas fa-diagram-project text-teal-500"></i> Tahapan Real-time Work-in-Progress (WIP)
        </h3>

        @php
            $stagesList = [
                'order_received' => ['label' => 'Order Diterima', 'icon' => 'fa-receipt'],
                'cutting' => ['label' => 'Potong Slep', 'icon' => 'fa-scissors'],
                'bubut_gerinda' => ['label' => 'Bubut & Gerinda', 'icon' => 'fa-gear'],
                'resin_polishing' => ['label' => 'Resin & Ampelas', 'icon' => 'fa-layer-group'],
                'qc_pertama' => ['label' => 'QC Pertama', 'icon' => 'fa-clipboard-check'],
                'finishing' => ['label' => 'Poles Hi-Glossy', 'icon' => 'fa-wand-magic-sparkles'],
                'qc_kedua' => ['label' => 'QC Final', 'icon' => 'fa-check-double'],
                'packing' => ['label' => 'Packing Sterofoam', 'icon' => 'fa-box-archive'],
                'ready_to_ship' => ['label' => 'Siap Ekspedisi', 'icon' => 'fa-truck-ramp-box'],
                'shipped' => ['label' => 'Terkirim', 'icon' => 'fa-truck-fast'],
            ];

            $keys = array_keys($stagesList);
            $currentIdx = array_search($order->status, $keys);
            if ($currentIdx === false) $currentIdx = 0;
        @endphp

        <div class="min-w-[900px]">
            <div class="relative flex items-center justify-between">
                <!-- Line background -->
                <div class="absolute left-6 right-6 top-5 h-1 bg-gray-200 dark:bg-slate-800 -z-0"></div>
                <!-- Line active progress -->
                <div class="absolute left-6 top-5 h-1 bg-teal-500 -z-0 transition-all duration-500" style="width: {{ count($keys) > 1 ? ($currentIdx / (count($keys) - 1)) * 100 : 0 }}%;"></div>

                @foreach($stagesList as $k => $step)
                    @php
                        $stepIdx = array_search($k, $keys);
                        $isCompleted = $stepIdx < $currentIdx;
                        $isCurrent = $stepIdx === $currentIdx;
                    @endphp
                    <div class="relative flex flex-col items-center group z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition-all shadow-md
                            {{ $isCompleted ? 'bg-teal-500 text-white' : ($isCurrent ? 'bg-amber-400 text-slate-900 ring-4 ring-amber-400/30 animate-pulse' : 'bg-gray-100 dark:bg-slate-800 text-gray-400 border border-gray-300 dark:border-slate-700') }}">
                            @if($isCompleted)
                                <i class="fas fa-check"></i>
                            @else
                                <i class="fas {{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <span class="text-[11px] font-semibold mt-2.5 text-center whitespace-nowrap {{ $isCurrent ? 'text-amber-500 font-bold' : ($isCompleted ? 'text-teal-600 dark:text-teal-400' : 'text-gray-400') }}">
                            {{ $step['label'] }}
                        </span>
                        @if($isCurrent)
                            <span class="text-[9px] px-2 py-0.5 rounded-full bg-amber-400/20 text-amber-500 font-bold uppercase mt-1">In Progress</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Update Stage Action Box -->
        <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <i class="fas fa-forward-step text-teal-500"></i> Update Status / Lanjut Tahap
            </h3>

            <form action="{{ route('pesanan.updateStatus', $order) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Pilih Tahapan Baru</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500 font-medium">
                        @foreach($stagesList as $k => $step)
                            <option value="{{ $k }}" {{ $order->status == $k ? 'selected' : '' }}>
                                {{ $loop->iteration }}. {{ $step['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Operator / Pengrajin Bertugas</label>
                    <input type="text" name="operator" placeholder="Contoh: Pak Joko / Mesin Bubut 3" class="w-full px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Catatan Tambahan</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan QC atau kondisi batuan..." class="w-full px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200"></textarea>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 transition-all">
                    Simpan Perubahan Tahapan
                </button>
            </form>
        </div>

        <!-- History Log Stages -->
        <div class="lg:col-span-2 glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <i class="fas fa-history text-cyan-500"></i> Riwayat Pencatatan Tahapan Produksi
            </h3>

            <div class="space-y-4">
                @forelse($order->productionStages as $stage)
                    <div class="p-4 rounded-2xl bg-gray-50/70 dark:bg-slate-800/50 border border-gray-100 dark:border-slate-800 flex items-start justify-between">
                        <div>
                            <span class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase">
                                {{ \App\Models\ProductionStage::STAGE_LABELS[$stage->tahap] ?? $stage->tahap }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                Operator: <strong class="text-gray-800 dark:text-gray-200">{{ $stage->operator ?? 'Tim Produksi' }}</strong>
                            </p>
                            @if($stage->catatan)
                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 bg-white dark:bg-slate-900 p-2 rounded-lg border border-gray-100 dark:border-slate-800 italic">
                                    "{{ $stage->catatan }}"
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $stage->status == 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($stage->status) }}
                            </span>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $stage->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada riwayat tahapan.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
