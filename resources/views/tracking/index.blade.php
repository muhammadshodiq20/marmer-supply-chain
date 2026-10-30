<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Status Pesanan Marmer - E-Supply Chain Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen selection:bg-teal-500 selection:text-white flex flex-col justify-between">

    <!-- Top Navbar -->
    <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-400 flex items-center justify-center text-white shadow-md">
                    <i class="fas fa-cubes-stacked"></i>
                </div>
                <div>
                    <span class="text-lg font-extrabold text-white">MarmerSC</span>
                    <span class="block text-[10px] text-teal-400 font-semibold uppercase">Layanan Lacak Progres WIP</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('catalog') }}" class="text-xs font-semibold text-slate-300 hover:text-teal-400 transition-colors">
                    <i class="fas fa-store mr-1"></i> Katalog
                </a>
                <a href="{{ route('pesanan.public_create') }}" class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-md shadow-teal-500/20 transition-all">
                    <i class="fas fa-plus mr-1"></i> Pesan Kustom
                </a>
                <a href="{{ route('dashboard') }}" class="text-xs text-slate-400 hover:text-white px-3 py-2 rounded-lg border border-slate-800">
                    Masuk Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-6 py-12 flex-1 w-full">
        <!-- Search Box -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-teal-400 bg-teal-500/10 px-3 py-1 rounded-full border border-teal-500/20">
                Self-Service Tracking
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-white mt-3">Lacak Status Pesanan Anda</h1>
            <p class="text-sm text-slate-400 mt-2">
                Pantau langsung perjalanan balok marmer Anda dari pemotongan slep, pembubutan, pelapisan resin, poles kilap hingga siap dikirim ke lokasi Anda.
            </p>

            <form action="{{ route('tracking.index') }}" method="GET" class="mt-8 flex flex-col sm:flex-row items-center gap-3 max-w-lg mx-auto">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                    <input type="text" name="kode_order" value="{{ request('kode_order') }}" placeholder="Masukkan Kode Order (cth: ORD-20260801-001)" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm font-mono shadow-inner">
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-extrabold text-sm shadow-lg shadow-teal-500/30 whitespace-nowrap transition-all">
                    Lacak Pesanan
                </button>
            </form>

            <!-- Sample hint codes -->
            <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-slate-500">
                <span>Coba kode contoh:</span>
                <a href="{{ route('tracking.index', ['kode_order' => 'ORD-20260801-001']) }}" class="font-mono text-teal-400 hover:underline">ORD-20260801-001</a> &bull;
                <a href="{{ route('tracking.index', ['kode_order' => 'ORD-20260804-002']) }}" class="font-mono text-teal-400 hover:underline">ORD-20260804-002</a> &bull;
                <a href="{{ route('tracking.index', ['kode_order' => 'ORD-20260810-003']) }}" class="font-mono text-teal-400 hover:underline">ORD-20260810-003</a>
            </div>
        </div>

        @if(session('success'))
            <div class="max-w-3xl mx-auto mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($searched && !$order)
            <div class="max-w-xl mx-auto text-center p-12 rounded-3xl bg-slate-900/50 border border-slate-800">
                <i class="fas fa-box-open text-slate-600 text-5xl mb-4"></i>
                <h3 class="text-lg font-bold text-white">Pesanan Tidak Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1">Pastikan kode order yang dimasukkan sudah benar atau hubungi pengrajin marmer terkait.</p>
            </div>
        @endif

        @if($order)
            <!-- Detail Card -->
            <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl space-y-8">
                <!-- Header Info -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-800 gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20">
                                {{ $order->ikmProfile->nama_usaha }}
                            </span>
                            <span class="text-xs font-mono text-slate-400">{{ $order->kode_order }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-white mt-2">{{ $order->jenis_produk }} ({{ $order->jumlah }} Unit)</h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Pemesan: <strong class="text-slate-200">{{ $order->nama_pelanggan }}</strong> &bull; Tujuan: <strong class="text-slate-200">{{ $order->alamat_pengiriman }}</strong>
                        </p>
                    </div>

                    <div class="text-right sm:self-center">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold {{ $order->status_color }}">
                            <span class="w-2 h-2 rounded-full bg-current animate-ping"></span>
                            {{ $order->status_label }}
                        </span>
                        <p class="text-[11px] text-slate-500 mt-1.5">Tgl Masuk: {{ $order->tanggal_order->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Spesifikasi Buyer -->
                <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800">
                    <span class="text-[10px] font-bold text-teal-400 uppercase tracking-wider block mb-1">
                        <i class="fas fa-clipboard-check mr-1"></i> Spesifikasi Corak & Dimensi Pesanan Anda
                    </span>
                    <p class="text-xs text-slate-300 leading-relaxed">{{ $order->spesifikasi ?? 'Sesuai standar batuan alam Tulungagung.' }}</p>
                </div>

                <!-- Timeline Stages -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <i class="fas fa-timeline text-teal-400"></i> Progres Tahapan Pengerjaan Real-time
                    </h3>

                    @php
                        $stagesList = [
                            'order_received' => 'Order Diterima',
                            'cutting' => 'Potong Slep Batu',
                            'bubut_gerinda' => 'Bubut & Gerinda',
                            'resin_polishing' => 'Penambalan Resin & Ampelas',
                            'qc_pertama' => 'QC Pertama (Cek Retak)',
                            'finishing' => 'Poles Akhir Hi-Glossy',
                            'qc_kedua' => 'QC Final (Kilau & Afur)',
                            'packing' => 'Packing Sterofoam & Crate',
                            'ready_to_ship' => 'Siap Ekspedisi',
                            'shipped' => 'Terkirim ke Pembeli',
                        ];
                        $keys = array_keys($stagesList);
                        $currentIdx = array_search($order->status, $keys);
                        if ($currentIdx === false) $currentIdx = 0;
                    @endphp

                    <div class="space-y-4 relative before:absolute before:inset-0 before:left-4 before:h-full before:w-0.5 before:bg-slate-800">
                        @foreach($stagesList as $k => $label)
                            @php
                                $stepIdx = array_search($k, $keys);
                                $isCompleted = $stepIdx < $currentIdx;
                                $isCurrent = $stepIdx === $currentIdx;
                            @endphp
                            <div class="relative flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 z-10 transition-all shadow-md
                                    {{ $isCompleted ? 'bg-teal-500 text-slate-950' : ($isCurrent ? 'bg-amber-400 text-slate-950 ring-4 ring-amber-400/30 animate-pulse' : 'bg-slate-900 text-slate-600 border border-slate-800') }}">
                                    @if($isCompleted)
                                        <i class="fas fa-check"></i>
                                    @elseif($isCurrent)
                                        <i class="fas fa-spinner fa-spin"></i>
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>

                                <div class="p-3.5 rounded-2xl flex-1 border transition-all
                                    {{ $isCurrent ? 'bg-slate-900 border-amber-500/30' : ($isCompleted ? 'bg-slate-950 border-teal-500/20' : 'bg-slate-950/40 border-slate-900') }}">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold {{ $isCurrent ? 'text-amber-400 font-extrabold' : ($isCompleted ? 'text-white' : 'text-slate-600') }}">
                                            {{ $label }}
                                        </h4>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md
                                            {{ $isCompleted ? 'bg-teal-500/10 text-teal-400' : ($isCurrent ? 'bg-amber-400/20 text-amber-400 uppercase font-bold' : 'text-slate-700') }}">
                                            {{ $isCompleted ? 'Selesai' : ($isCurrent ? 'Sedang Dikerjakan' : 'Menunggu') }}
                                        </span>
                                    </div>
                                    @if($isCurrent)
                                        <p class="text-[11px] text-slate-400 mt-1">Stasiun ini sedang dalam pengerjaan pengrajin di bengkel.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Kontak Perajin -->
                <div class="pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                    <span>Butuh konfirmasi pengiriman? Hubungi perajin: <strong class="text-teal-400">{{ $order->ikmProfile->telepon ?? '0812-xxxx-xxxx' }}</strong></span>
                    <a href="https://wa.me/?text=Halo%20saya%20ingin%20tanya%20progres%20order%20{{ $order->kode_order }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-2">
                        <i class="fab fa-whatsapp"></i> Chat WhatsApp Perajin
                    </a>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="py-8 border-t border-slate-900 text-center text-xs text-slate-600">
        &copy; 2026 E-Supply Chain Marmer Tulungagung. All rights reserved.
    </footer>

</body>
</html>
