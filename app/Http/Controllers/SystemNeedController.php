<?php

namespace App\Http\Controllers;

use App\Models\SystemNeed;
use App\Models\IkmProfile;
use Illuminate\Http\Request;

class SystemNeedController extends Controller
{
    public function index(Request $request)
    {
        $ikmProfiles = IkmProfile::all();
        $selectedIkmId = $request->get('ikm', $ikmProfiles->first()?->id);
        $selectedIkm = $ikmProfiles->where('id', $selectedIkmId)->first() ?? $ikmProfiles->first();

        $systemNeeds = SystemNeed::where('ikm_profile_id', $selectedIkm->id)->latest('tanggal')->get();

        return view('kebutuhan-sistem.index', compact('ikmProfiles', 'selectedIkm', 'systemNeeds'));
    }
}
