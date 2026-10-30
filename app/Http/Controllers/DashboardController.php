<?php

namespace App\Http\Controllers;

use App\Models\IkmProfile;
use App\Models\Order;
use App\Models\ProductionRecord;
use App\Models\RawMaterial;
use App\Models\WasteRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBahanBaku = RawMaterial::count();
        $totalProduk = ProductionRecord::count();
        $totalPesananAktif = Order::whereNotIn('status', ['shipped'])->count();
        $totalLimbah = WasteRecord::count();
        $recentOrders = Order::with('ikmProfile')->latest()->take(5)->get();
        $ikmProfiles = IkmProfile::withCount(['orders', 'rawMaterials', 'wasteRecords'])->get();

        // Production chart data
        $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus'];
        $cahayaData = [];
        $putraData = [];
        foreach ($months as $m) {
            $cahayaData[] = ProductionRecord::whereHas('ikmProfile', fn($q) => $q->where('nama_usaha', 'UD Cahaya Onix'))
                ->where('bulan', $m)->sum(\DB::raw("CAST(REPLACE(REPLACE(jumlah_produksi, '.', ''), ' Pcs', '') AS UNSIGNED)"));
            $putraData[] = ProductionRecord::whereHas('ikmProfile', fn($q) => $q->where('nama_usaha', 'UD Putra Abadi'))
                ->where('bulan', $m)->sum(\DB::raw("CAST(REPLACE(REPLACE(jumlah_produksi, '.', ''), ' Pcs', '') AS UNSIGNED)"));
        }
        $productionChartData = json_encode([
            'labels' => $months,
            'datasets' => [
                ['label' => 'UD Cahaya Onix', 'data' => $cahayaData, 'borderColor' => '#14b8a6', 'backgroundColor' => 'rgba(20,184,166,0.1)'],
                ['label' => 'UD Putra Abadi', 'data' => $putraData, 'borderColor' => '#f59e0b', 'backgroundColor' => 'rgba(245,158,11,0.1)'],
            ]
        ]);

        // Waste chart data
        $wasteTypes = WasteRecord::select('jenis_waste', \DB::raw('count(*) as total'))->groupBy('jenis_waste')->get();
        $wasteChartData = json_encode([
            'labels' => $wasteTypes->pluck('jenis_waste')->toArray(),
            'data' => $wasteTypes->pluck('total')->toArray(),
        ]);

        return view('dashboard.index', compact(
            'totalBahanBaku', 'totalProduk', 'totalPesananAktif', 'totalLimbah',
            'recentOrders', 'productionChartData', 'wasteChartData', 'ikmProfiles'
        ));
    }

    public function landing()
    {
        $ikmProfiles = IkmProfile::withCount(['products', 'orders'])->get();
        $totalProduction = ProductionRecord::count();
        $totalOrders = Order::count();
        return view('landing', compact('ikmProfiles', 'totalProduction', 'totalOrders'));
    }
}
