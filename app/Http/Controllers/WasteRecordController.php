<?php

namespace App\Http\Controllers;

use App\Models\WasteRecord;
use App\Models\IkmProfile;
use Illuminate\Http\Request;

class WasteRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = WasteRecord::with(['ikmProfile', 'wasteUtilizations']);
        if ($request->filled('ikm')) $query->where('ikm_profile_id', $request->ikm);
        if ($request->filled('waste')) $query->where('jenis_waste', 'like', '%'.$request->waste.'%');
        
        $wasteRecords = $query->latest('tanggal')->paginate(15)->appends($request->query());
        $ikmProfiles = IkmProfile::all();

        // Rekap statistik waste per jenis
        $wasteStats = WasteRecord::select('jenis_waste', \DB::raw('count(*) as count'))
            ->groupBy('jenis_waste')
            ->get();

        return view('limbah.index', compact('wasteRecords', 'ikmProfiles', 'wasteStats'));
    }

    public function create()
    {
        $ikmProfiles = IkmProfile::all();
        return view('limbah.create', compact('ikmProfiles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'tanggal' => 'required|date',
            'aktivitas_diamati' => 'required|string',
            'waktu_mulai' => 'required|string',
            'waktu_selesai' => 'required|string',
            'jenis_waste' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        WasteRecord::create($validated);
        return redirect()->route('limbah.index')->with('success', 'Observasi limbah/waste berhasil dicatat.');
    }

    public function show(WasteRecord $wasteRecord)
    {
        $wasteRecord->load(['ikmProfile', 'wasteUtilizations']);
        return view('limbah.show', compact('wasteRecord'));
    }

    public function edit(WasteRecord $wasteRecord)
    {
        $ikmProfiles = IkmProfile::all();
        return view('limbah.edit', compact('wasteRecord', 'ikmProfiles'));
    }

    public function update(Request $request, WasteRecord $wasteRecord)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'tanggal' => 'required|date',
            'aktivitas_diamati' => 'required|string',
            'waktu_mulai' => 'required|string',
            'waktu_selesai' => 'required|string',
            'jenis_waste' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $wasteRecord->update($validated);
        return redirect()->route('limbah.index')->with('success', 'Observasi limbah berhasil diperbarui.');
    }

    public function destroy(WasteRecord $wasteRecord)
    {
        $wasteRecord->delete();
        return redirect()->route('limbah.index')->with('success', 'Data observasi limbah berhasil dihapus.');
    }

    public function analytics()
    {
        $ikmProfiles = IkmProfile::all();
        $wasteByType = WasteRecord::select('jenis_waste', \DB::raw('count(*) as total'))
            ->groupBy('jenis_waste')
            ->get();
            
        return view('limbah.analytics', compact('ikmProfiles', 'wasteByType'));
    }
}
