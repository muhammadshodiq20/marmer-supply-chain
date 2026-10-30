<?php

namespace App\Http\Controllers;

use App\Models\WasteUtilization;
use App\Models\IkmProfile;
use App\Models\WasteRecord;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class WasteUtilizationController extends Controller
{
    public function index(Request $request)
    {
        $query = WasteUtilization::with(['ikmProfile', 'wasteRecord']);
        if ($request->filled('ikm')) $query->where('ikm_profile_id', $request->ikm);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('pemanfaatan')) $query->where('pemanfaatan', 'like', '%'.$request->pemanfaatan.'%');
        
        $utilizations = $query->latest()->paginate(15)->appends($request->query());
        $ikmProfiles = IkmProfile::all();

        $totalValuation = WasteUtilization::where('status', 'terjual')->sum('harga_jual');
        $itemCount = WasteUtilization::count();

        return view('pemanfaatan-limbah.index', compact('utilizations', 'ikmProfiles', 'totalValuation', 'itemCount'));
    }

    public function create()
    {
        $ikmProfiles = IkmProfile::all();
        $wasteRecords = WasteRecord::latest()->take(20)->get();
        return view('pemanfaatan-limbah.create', compact('ikmProfiles', 'wasteRecords'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'waste_record_id' => 'nullable|exists:waste_records,id',
            'jenis_limbah' => 'required|string|max:255',
            'volume_estimasi' => 'nullable|string|max:255',
            'pemanfaatan' => 'required|string|max:255',
            'status' => 'required|string',
            'harga_jual' => 'nullable|numeric',
            'pembeli' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $item = WasteUtilization::create($validated);

        ActivityLog::log('Tambah Data Limbah', "Menambahkan pemanfaatan limbah: {$item->jenis_limbah} ({$item->pemanfaatan})");

        return redirect()->route('pemanfaatan-limbah.index')->with('success', 'Data hilirisasi/pemanfaatan limbah berhasil ditambahkan.');
    }

    public function show(WasteUtilization $wasteUtilization)
    {
        $wasteUtilization->load(['ikmProfile', 'wasteRecord']);
        return view('pemanfaatan-limbah.show', compact('wasteUtilization'));
    }

    public function edit(WasteUtilization $wasteUtilization)
    {
        $ikmProfiles = IkmProfile::all();
        $wasteRecords = WasteRecord::latest()->take(20)->get();
        return view('pemanfaatan-limbah.edit', compact('wasteUtilization', 'ikmProfiles', 'wasteRecords'));
    }

    public function update(Request $request, WasteUtilization $wasteUtilization)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'waste_record_id' => 'nullable|exists:waste_records,id',
            'jenis_limbah' => 'required|string|max:255',
            'volume_estimasi' => 'nullable|string|max:255',
            'pemanfaatan' => 'required|string|max:255',
            'status' => 'required|string',
            'harga_jual' => 'nullable|numeric',
            'pembeli' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $wasteUtilization->update($validated);

        ActivityLog::log('Edit Data Limbah', "Memperbarui data hilirisasi limbah ID: {$wasteUtilization->id} ({$wasteUtilization->jenis_limbah})");

        return redirect()->route('pemanfaatan-limbah.index')->with('success', 'Data pemanfaatan limbah berhasil diperbarui.');
    }

    public function destroy(WasteUtilization $wasteUtilization)
    {
        $jenis = $wasteUtilization->jenis_limbah;
        $wasteUtilization->delete();

        ActivityLog::log('Hapus Data Limbah', "Menghapus data pemanfaatan limbah: {$jenis}");

        return redirect()->route('pemanfaatan-limbah.index')->with('success', 'Data pemanfaatan limbah berhasil dihapus.');
    }
}
