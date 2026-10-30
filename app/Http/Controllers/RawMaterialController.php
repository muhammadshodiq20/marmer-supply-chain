<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\IkmProfile;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = RawMaterial::with('ikmProfile');
        if ($request->filled('jenis_batu')) $query->where('jenis_batu', $request->jenis_batu);
        if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
        if ($request->filled('ikm')) $query->where('ikm_profile_id', $request->ikm);
        $rawMaterials = $query->latest()->paginate(15)->appends($request->query());
        $ikmProfiles = IkmProfile::all();
        return view('bahan-baku.index', compact('rawMaterials', 'ikmProfiles'));
    }

    public function create()
    {
        $ikmProfiles = IkmProfile::all();
        return view('bahan-baku.create', compact('ikmProfiles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'jenis_batu' => 'required|string',
            'jumlah_pembelian' => 'required|string',
            'dimensi' => 'nullable|string',
            'warna' => 'nullable|string',
            'status' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);
        RawMaterial::create($validated);
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function show(RawMaterial $rawMaterial)
    {
        $rawMaterial->load('ikmProfile');
        return view('bahan-baku.show', compact('rawMaterial'));
    }

    public function edit(RawMaterial $rawMaterial)
    {
        $ikmProfiles = IkmProfile::all();
        return view('bahan-baku.edit', compact('rawMaterial', 'ikmProfiles'));
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'jenis_batu' => 'required|string',
            'jumlah_pembelian' => 'required|string',
            'dimensi' => 'nullable|string',
            'warna' => 'nullable|string',
            'status' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);
        $rawMaterial->update($validated);
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil dihapus.');
    }
}
