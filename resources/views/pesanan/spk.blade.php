<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPK Produksi - {{ $order->kode_order }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-code { font-family: 'Courier Prime', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 text-gray-900">

    <!-- Action Bar (Hidden on Print) -->
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('pesanan.show', $order) }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">
            &larr; Kembali ke Detail Pesanan
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak SPK Bengkel (Print)
        </button>
    </div>

    <!-- Paper Sheet SPK -->
    <div class="max-w-3xl mx-auto bg-white border border-gray-300 p-10 shadow-lg rounded-2xl">
        <!-- Header -->
        <div class="border-b-2 border-gray-900 pb-6 mb-6 flex items-start justify-between">
            <div>
                <span class="text-xs uppercase font-extrabold tracking-widest text-teal-700">Surat Perintah Kerja (SPK) Bengkel</span>
                <h1 class="text-2xl font-black text-gray-900 mt-1">{{ $order->ikmProfile->nama_usaha }}</h1>
                <p class="text-xs text-gray-600">{{ $order->ikmProfile->lokasi }} &bull; {{ $order->ikmProfile->telepon }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-mono-code font-bold text-gray-500 block">KODE DOKUMEN</span>
                <span class="text-lg font-mono-code font-black text-teal-700">{{ $order->kode_order }}</span>
                <p class="text-[11px] text-gray-500 mt-1">Tanggal: {{ $order->tanggal_order->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Info Order & Buyer -->
        <div class="grid grid-cols-2 gap-6 p-4 rounded-xl bg-gray-50 border border-gray-200 mb-6 text-xs">
            <div>
                <span class="font-bold text-gray-500 block uppercase text-[10px]">Nama Pelanggan</span>
                <p class="font-extrabold text-gray-900 text-sm mt-0.5">{{ $order->nama_pelanggan }}</p>
                <p class="text-gray-600">{{ $order->telepon_pelanggan ?? '-' }}</p>
            </div>
            <div>
                <span class="font-bold text-gray-500 block uppercase text-[10px]">Tujuan Ekspedisi</span>
                <p class="font-bold text-gray-900 mt-0.5">{{ $order->alamat_pengiriman ?? 'Tulungagung' }}</p>
                <span class="text-[10px] text-amber-700 font-semibold">*Ongkos kirim ditanggung pembeli</span>
            </div>
        </div>

        <!-- Spesifikasi Produksi -->
        <div class="mb-6">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-800 border-b border-gray-200 pb-2 mb-3">
                1. Rincian Produk & Instruksi Pembentukan
            </h2>
            <table class="w-full text-xs text-left border border-gray-200">
                <thead class="bg-gray-100 uppercase text-[10px] text-gray-600">
                    <tr>
                        <th class="p-3 border-b">Item Produk</th>
                        <th class="p-3 border-b text-center">Jumlah</th>
                        <th class="p-3 border-b">Instruksi Ukuran & Corak Batuan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-3 font-bold border-b">{{ $order->jenis_produk }}</td>
                        <td class="p-3 font-extrabold text-center border-b text-base">{{ $order->jumlah }} Unit</td>
                        <td class="p-3 border-b leading-relaxed">{{ $order->spesifikasi ?? 'Ukuran standar bubut Tulungagung.' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Checklist Stasiun Kerja Bengkel -->
        <div class="mb-8">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-800 border-b border-gray-200 pb-2 mb-3">
                2. Checklist Kontrol Stasiun Kerja & QC
            </h2>
            <table class="w-full text-xs text-left border border-gray-200">
                <thead class="bg-gray-100 uppercase text-[10px] text-gray-600">
                    <tr>
                        <th class="p-2.5 border-b w-8 text-center">No</th>
                        <th class="p-2.5 border-b">Stasiun Kerja</th>
                        <th class="p-2.5 border-b">Standar Kualitas</th>
                        <th class="p-2.5 border-b w-24">Paraf Operator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="p-2.5 text-center font-bold">1</td>
                        <td class="p-2.5 font-bold">Pemotongan Mesin Slep</td>
                        <td class="p-2.5 text-gray-600">Toleransi tinggi &plusmn; 80 cm, periksa retak bongkahan</td>
                        <td class="p-2.5 border-l"></td>
                    </tr>
                    <tr>
                        <td class="p-2.5 text-center font-bold">2</td>
                        <td class="p-2.5 font-bold">Pembubutan & Gerinda</td>
                        <td class="p-2.5 text-gray-600">Ketebalan dinding seragam, lubang afur drainase presisi</td>
                        <td class="p-2.5 border-l"></td>
                    </tr>
                    <tr>
                        <td class="p-2.5 text-center font-bold">3</td>
                        <td class="p-2.5 font-bold">QC 1 & Penambalan Resin</td>
                        <td class="p-2.5 text-gray-600">Retak halus ditambal resin + katalis corak senada</td>
                        <td class="p-2.5 border-l"></td>
                    </tr>
                    <tr>
                        <td class="p-2.5 text-center font-bold">4</td>
                        <td class="p-2.5 font-bold">Poles 5 Tahap & Hi-Glossy</td>
                        <td class="p-2.5 text-gray-600">Pengamplasan bertingkat, kilap maksimal tanpa pori</td>
                        <td class="p-2.5 border-l"></td>
                    </tr>
                    <tr>
                        <td class="p-2.5 text-center font-bold">5</td>
                        <td class="p-2.5 font-bold">QC 2 & Packing Aman</td>
                        <td class="p-2.5 text-gray-600">Sterofoam kardus rapat + krat kayu luar pulau</td>
                        <td class="p-2.5 border-l"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tanda Tangan -->
        <div class="grid grid-cols-3 gap-6 text-center text-xs pt-4 border-t border-gray-200">
            <div>
                <span class="text-gray-500 block mb-12">Pemilik / Pengawas</span>
                <span class="font-bold border-b border-gray-400 pb-1">({{ $order->ikmProfile->nama_pemilik }})</span>
            </div>
            <div>
                <span class="text-gray-500 block mb-12">Operator Bubut / Slep</span>
                <span class="font-bold border-b border-gray-400 pb-1">( ................................ )</span>
            </div>
            <div>
                <span class="text-gray-500 block mb-12">Bagian Ekspedisi</span>
                <span class="font-bold border-b border-gray-400 pb-1">( ................................ )</span>
            </div>
        </div>
    </div>

</body>
</html>
