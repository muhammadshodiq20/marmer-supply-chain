<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi Operasional E-Supply Chain IKM Marmer</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; padding: 20px; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #0d9488; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 0; text-transform: uppercase; color: #0f766e; }
        .header p { margin: 4px 0 0; color: #64748b; font-size: 10px; }
        .stats { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .stat-box { border: 1px solid #cbd5e1; padding: 10px; border-radius: 6px; width: 30%; text-align: center; }
        .stat-box h4 { margin: 0; font-size: 10px; text-transform: uppercase; color: #64748b; }
        .stat-box p { margin: 5px 0 0; font-size: 14px; font-weight: bold; color: #0d9488; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background-color: #f1f5f9; font-size: 10px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .no-print { margin-bottom: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #0d9488; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Dokumen / Simpan PDF
        </button>
    </div>

    <div class="header">
        <h1>Laporan Resmi Operasional & Rantai Pasok IKM Marmer</h1>
        <p>Sentra Kerajinan Marmer & Batu Alam Campurdarat, Kabupaten Tulungagung &bull; Tahun 2026</p>
        <p style="font-size: 9px; color: #94a3b8;">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB &bull; Sistem E-Supply Chain</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <h4>Total Pendapatan Terverifikasi</h4>
            <p>Rp {{ number_format($pendapatanSelesai, 0, ',', '.') }}</p>
        </div>
        <div class="stat-box">
            <h4>Total Pesanan Terdata</h4>
            <p>{{ $orders->count() }} Pesanan</p>
        </div>
        <div class="stat-box">
            <h4>Stok Balok Bahan Baku</h4>
            <p>{{ $rawMaterials->count() }} Batch Terdata</p>
        </div>
    </div>

    <h3 style="font-size: 12px; margin-bottom: 6px; color: #0f766e;">1. Rekapitulasi Pesanan & Transaksi Penjualan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Order</th>
                <th>Sentra IKM</th>
                <th>Pelanggan</th>
                <th>Produk & Jumlah</th>
                <th>Status WIP</th>
                <th class="text-right">Nilai Transaksi (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $idx => $o)
                @php
                    $nilai = $o->total_harga > 0 ? $o->total_harga : ($o->jumlah * 450000);
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ $o->kode_order }}</strong></td>
                    <td>{{ $o->ikmProfile->nama_usaha ?? '-' }}</td>
                    <td>{{ $o->nama_pelanggan }} ({{ $o->telepon_pelanggan }})</td>
                    <td>{{ $o->jenis_produk }} - {{ $o->jumlah }} Pcs</td>
                    <td>{{ $o->status_label }}</td>
                    <td class="text-right font-bold">{{ number_format($nilai, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="font-size: 12px; margin-bottom: 6px; color: #0f766e;">2. Hilirisasi & Pemanfaatan Limbah (Circular Economy)</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Sentra IKM</th>
                <th>Jenis Limbah</th>
                <th>Volume Estimasi</th>
                <th>Hilirisasi / Pemanfaatan</th>
                <th>Status</th>
                <th class="text-right">Nilai Jual (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wasteUtilizations as $idx => $w)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $w->ikmProfile->nama_usaha ?? '-' }}</td>
                    <td><strong>{{ $w->jenis_limbah }}</strong></td>
                    <td>{{ $w->volume_estimasi ?? '-' }}</td>
                    <td>{{ $w->pemanfaatan }}</td>
                    <td>{{ ucfirst($w->status) }}</td>
                    <td class="text-right">{{ $w->harga_jual ? number_format($w->harga_jual, 0, ',', '.') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
        <div style="text-align: center; width: 200px;">
            <p>Tulungagung, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 50px;">Mengetahui, Pengelola Sentra IKM</p>
            <p><strong>( M. Ilham Nur Amali )</strong></p>
            <p style="font-size: 9px; color: #64748b;">Pimpinan UD Cahaya Onix</p>
        </div>
    </div>

</body>
</html>
