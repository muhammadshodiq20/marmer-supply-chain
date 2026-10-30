<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionRecord;
use App\Models\RawMaterial;
use App\Models\WasteUtilization;
use App\Models\IkmProfile;
use App\Models\User;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    public function index(Request $request)
    {
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $selectedBulan = $request->input('bulan');
        $selectedTahun = $request->input('tahun', date('Y'));
        $selectedIkm = $request->input('ikm_id');

        $ikmProfiles = IkmProfile::all();

        // 1. Data Pesanan & Keuangan Transaksi
        $orderQuery = Order::with('ikmProfile');
        if ($selectedIkm) $orderQuery->where('ikm_profile_id', $selectedIkm);
        if ($selectedTahun) $orderQuery->whereYear('tanggal_order', $selectedTahun);
        $orders = $orderQuery->latest()->get();

        // Ringkasan Keuangan: Pendapatan Nyata vs Potensi
        $pendapatanSelesai = $orders->whereIn('status', ['shipped', 'ready_to_ship'])->sum(function($o) {
            return $o->total_harga > 0 ? $o->total_harga : ($o->jumlah * 450000); // estimasi nilai pesanan bila nominal kosong
        });

        $totalPesananMasuk = $orders->count();
        $pesananSelesaiCount = $orders->where('status', 'shipped')->count();
        $pesananWipCount = $orders->whereNotIn('status', ['shipped'])->count();

        // 2. Data Bahan Baku
        $rawMaterialQuery = RawMaterial::with('ikmProfile');
        if ($selectedIkm) $rawMaterialQuery->where('ikm_profile_id', $selectedIkm);
        $rawMaterials = $rawMaterialQuery->get();

        // 3. Data Produksi Output
        $prodQuery = ProductionRecord::with('ikmProfile');
        if ($selectedIkm) $prodQuery->where('ikm_profile_id', $selectedIkm);
        if ($selectedBulan) $prodQuery->where('bulan', $selectedBulan);
        $productionRecords = $prodQuery->get();

        // 4. Data Hilirisasi Limbah (Circular Economy)
        $wasteUtilQuery = WasteUtilization::with('ikmProfile');
        if ($selectedIkm) $wasteUtilQuery->where('ikm_profile_id', $selectedIkm);
        $wasteUtilizations = $wasteUtilQuery->get();
        $totalPendapatanLimbah = $wasteUtilizations->where('status', 'terjual')->sum('harga_jual');

        return view('laporan.index', compact(
            'orders', 'rawMaterials', 'productionRecords', 'wasteUtilizations',
            'ikmProfiles', 'bulanList', 'selectedBulan', 'selectedTahun', 'selectedIkm',
            'pendapatanSelesai', 'totalPesananMasuk', 'pesananSelesaiCount', 'pesananWipCount',
            'totalPendapatanLimbah'
        ));
    }

    public function exportExcel(Request $request)
    {
        $tipe = $request->input('tipe', 'semua');
        $fileName = 'Laporan_Supply_Chain_Marmer_' . ucfirst($tipe) . '_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $fileName);

        $output = fopen('php://output', 'w');
        // BOM UTF-8 agar karakter rapi di Microsoft Excel Windows
        fputs($output, "\xEF\xBB\xBF");

        if ($tipe === 'pesanan' || $tipe === 'semua') {
            fputcsv($output, ['=== LAPORAN TRANSAKSI & PESANAN WIP ===']);
            fputcsv($output, ['Kode Order', 'Sentra IKM', 'Pelanggan', 'No WhatsApp', 'Jenis Produk', 'Jumlah', 'Total Nilai (Rp)', 'Status Produksi', 'Status Bayar', 'Tgl Order']);
            
            $orders = Order::with('ikmProfile')->latest()->get();
            foreach ($orders as $o) {
                $nilai = $o->total_harga > 0 ? $o->total_harga : ($o->jumlah * 450000);
                fputcsv($output, [
                    $o->kode_order,
                    $o->ikmProfile->nama_usaha ?? '-',
                    $o->nama_pelanggan,
                    $o->telepon_pelanggan,
                    $o->jenis_produk,
                    $o->jumlah,
                    number_format($nilai, 0, ',', '.'),
                    $o->status_label,
                    $o->status_pembayaran ?? 'Selesai/COD',
                    $o->tanggal_order?->format('d/m/Y')
                ]);
            }
            fputcsv($output, []);
        }

        if ($tipe === 'bahan_baku' || $tipe === 'semua') {
            fputcsv($output, ['=== LAPORAN INVENTARIS BAHAN BAKU BALOK MARMER ===']);
            fputcsv($output, ['Sentra IKM', 'Bahan Mentah', 'Dimensi / Spesifikasi', 'Variasi Corak/Warna', 'Ketersediaan', 'Supplier / Tambang']);
            $raws = RawMaterial::with('ikmProfile')->get();
            foreach ($raws as $r) {
                fputcsv($output, [
                    $r->ikmProfile->nama_usaha ?? '-',
                    $r->nama_bahan,
                    $r->dimensi_balok ?? $r->ukuran,
                    $r->variasi_warna ?? $r->warna,
                    $r->ketersediaan,
                    $r->supplier ?? 'Tambang Besole / Campurdarat'
                ]);
            }
            fputcsv($output, []);
        }

        if ($tipe === 'limbah' || $tipe === 'semua') {
            fputcsv($output, ['=== LAPORAN HILIRISASI & PENJUALAN LIMBAH (CIRCULAR ECONOMY) ===']);
            fputcsv($output, ['Sentra IKM', 'Jenis Limbah Sisa', 'Volume Estimasi', 'Bentuk Hilirisasi', 'Status', 'Nilai Penjualan (Rp)', 'Pembeli / Pihak Kedua']);
            $wastes = WasteUtilization::with('ikmProfile')->get();
            foreach ($wastes as $w) {
                fputcsv($output, [
                    $w->ikmProfile->nama_usaha ?? '-',
                    $w->jenis_limbah,
                    $w->volume_estimasi,
                    $w->pemanfaatan,
                    $w->status,
                    number_format($w->harga_jual ?? 0, 0, ',', '.'),
                    $w->pembeli ?? '-'
                ]);
            }
        }

        fclose($output);
        exit;
    }

    public function exportPdf(Request $request)
    {
        $ikmProfiles = IkmProfile::all();
        $orders = Order::with('ikmProfile')->latest()->take(30)->get();
        $rawMaterials = RawMaterial::with('ikmProfile')->get();
        $wasteUtilizations = WasteUtilization::with('ikmProfile')->get();

        $pendapatanSelesai = $orders->whereIn('status', ['shipped', 'ready_to_ship'])->sum(function($o) {
            return $o->total_harga > 0 ? $o->total_harga : ($o->jumlah * 450000);
        });

        return view('laporan.pdf', compact('ikmProfiles', 'orders', 'rawMaterials', 'wasteUtilizations', 'pendapatanSelesai'));
    }
}
