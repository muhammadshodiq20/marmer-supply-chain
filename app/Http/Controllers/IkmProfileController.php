<?php

namespace App\Http\Controllers;

use App\Models\IkmProfile;
use Illuminate\Http\Request;

class IkmProfileController extends Controller
{
    public function index()
    {
        $ikmProfiles = IkmProfile::withCount(['rawMaterials', 'products', 'orders', 'wasteRecords'])->get();
        return view('ikm.index', compact('ikmProfiles'));
    }

    public function show(IkmProfile $ikmProfile)
    {
        $ikmProfile->loadCount(['rawMaterials', 'products', 'orders', 'wasteRecords']);
        $recentMaterials = $ikmProfile->rawMaterials()->latest()->take(5)->get();
        $recentOrders = $ikmProfile->orders()->latest()->take(5)->get();
        $products = $ikmProfile->products()->take(6)->get();

        return view('ikm.show', compact('ikmProfile', 'recentMaterials', 'recentOrders', 'products'));
    }

    public function create()
    {
        return view('ikm.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'fokus_produksi' => 'required|string',
            'lokasi' => 'required|string',
            'kekuatan_produksi' => 'required|string',
            'deskripsi' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        IkmProfile::create($validated);
        return redirect()->route('ikm.index')->with('success', 'Profil IKM berhasil ditambahkan.');
    }

    public function edit(IkmProfile $ikmProfile)
    {
        return view('ikm.edit', compact('ikmProfile'));
    }

    public function update(Request $request, IkmProfile $ikmProfile)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'fokus_produksi' => 'required|string',
            'lokasi' => 'required|string',
            'kekuatan_produksi' => 'required|string',
            'deskripsi' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $ikmProfile->update($validated);
        return redirect()->route('ikm.show', $ikmProfile)->with('success', 'Profil IKM berhasil diperbarui.');
    }

    public function destroy(IkmProfile $ikmProfile)
    {
        $ikmProfile->delete();
        return redirect()->route('ikm.index')->with('success', 'Profil IKM berhasil dihapus.');
    }
}
