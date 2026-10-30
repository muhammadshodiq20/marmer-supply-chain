<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $order = null;
        $searched = false;

        if ($request->filled('kode_order')) {
            $searched = true;
            $kode = trim($request->kode_order);
            $order = Order::with(['ikmProfile', 'productionStages' => fn($q) => $q->orderBy('created_at', 'asc')])
                ->where('kode_order', $kode)
                ->first();
        }

        return view('tracking.index', compact('order', 'searched'));
    }
}
