<?php

namespace App\Http\Controllers;

use App\Models\ProcessFlow;
use App\Models\IkmProfile;
use Illuminate\Http\Request;

class ProcessFlowController extends Controller
{
    public function index(Request $request)
    {
        $ikmProfiles = IkmProfile::all();
        $selectedIkmId = $request->get('ikm', $ikmProfiles->first()?->id);
        
        $selectedIkm = $ikmProfiles->where('id', $selectedIkmId)->first() ?? $ikmProfiles->first();
        
        $query = ProcessFlow::where('ikm_profile_id', $selectedIkm->id)->orderBy('nomor_urut');
        if ($request->filled('konteks')) {
            $query->where('jenis_produk_konteks', $request->konteks);
        }
        $processFlows = $query->get();

        $konteksList = ProcessFlow::where('ikm_profile_id', $selectedIkm->id)
            ->whereNotNull('jenis_produk_konteks')
            ->distinct()
            ->pluck('jenis_produk_konteks');

        return view('alur-proses.index', compact('ikmProfiles', 'selectedIkm', 'processFlows', 'konteksList'));
    }

    public function show(IkmProfile $ikmProfile)
    {
        $processFlows = ProcessFlow::where('ikm_profile_id', $ikmProfile->id)
            ->orderBy('nomor_urut')
            ->get();
            
        return view('alur-proses.show', compact('ikmProfile', 'processFlows'));
    }
}
