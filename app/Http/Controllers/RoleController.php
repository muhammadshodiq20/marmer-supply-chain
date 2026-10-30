<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function switch(Request $request, string $role)
    {
        $validRoles = ['admin', 'operator', 'buyer'];
        if (in_array($role, $validRoles)) {
            session(['active_role' => $role]);
            
            if ($role === 'operator') {
                return redirect()->route('operator.index')->with('success', 'Beralih ke Mode Operator Bengkel (Shopfloor View)');
            } elseif ($role === 'buyer') {
                return redirect()->route('catalog')->with('success', 'Beralih ke Mode Pelanggan / Buyer (Showcase & Tracking View)');
            } else {
                return redirect()->route('dashboard')->with('success', 'Beralih ke Mode Pemilik IKM (Full Admin View)');
            }
        }

        return back();
    }
}
