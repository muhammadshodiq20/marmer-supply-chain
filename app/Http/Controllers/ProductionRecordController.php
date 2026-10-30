<?php

namespace App\Http\Controllers;

use App\Models\ProductionRecord;
use App\Models\IkmProfile;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductionRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductionRecord::with('ikmProfile');
        if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('jenis_produk')) $query->where('jenis_produk', 'like', '%'.$request->jenis_produk.'%');
        if ($request->filled('ikm')) $query->where('ikm_profile_id', $request->ikm);
        $records = $query->orderBy('tahun', 'desc')->orderByRaw("FIELD(bulan,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')")->paginate(20)->appends($request->query());
        $ikmProfiles = IkmProfile::all();
        return view('produksi.index', compact('records', 'ikmProfiles'));
    }

    public function create()
    {
        $ikmProfiles = IkmProfile::all();
        $products = Product::all();
        return view('produksi.create', compact('ikmProfiles', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'product_id' => 'nullable|exists:products,id',
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'jenis_produk' => 'required|string',
            'jumlah_produksi' => 'required|string',
            'catatan' => 'nullable|string',
        ]);
        ProductionRecord::create($validated);
        return redirect()->route('produksi.index')->with('success', 'Rekap produksi berhasil ditambahkan.');
    }

    public function show(ProductionRecord $productionRecord)
    {
        $productionRecord->load('ikmProfile', 'product');
        return view('produksi.show', compact('productionRecord'));
    }

    public function edit(ProductionRecord $productionRecord)
    {
        $ikmProfiles = IkmProfile::all();
        $products = Product::all();
        return view('produksi.edit', compact('productionRecord', 'ikmProfiles', 'products'));
    }

    public function update(Request $request, ProductionRecord $productionRecord)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'product_id' => 'nullable|exists:products,id',
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'jenis_produk' => 'required|string',
            'jumlah_produksi' => 'required|string',
            'catatan' => 'nullable|string',
        ]);
        $productionRecord->update($validated);
        return redirect()->route('produksi.index')->with('success', 'Rekap produksi berhasil diperbarui.');
    }

    public function destroy(ProductionRecord $productionRecord)
    {
        $productionRecord->delete();
        return redirect()->route('produksi.index')->with('success', 'Rekap produksi berhasil dihapus.');
    }

    public function chart()
    {
        $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus'];
        $data = [];
        $ikmProfiles = IkmProfile::all();
        foreach ($ikmProfiles as $ikm) {
            $monthlyData = [];
            foreach ($months as $m) {
                $monthlyData[] = ProductionRecord::where('ikm_profile_id', $ikm->id)
                    ->where('bulan', $m)->sum(\DB::raw("CAST(REPLACE(REPLACE(jumlah_produksi, '.', ''), ' Pcs', '') AS UNSIGNED)"));
            }
            $data[] = ['label' => $ikm->nama_usaha, 'data' => $monthlyData];
        }
        return response()->json(['labels' => $months, 'datasets' => $data]);
    }
}
