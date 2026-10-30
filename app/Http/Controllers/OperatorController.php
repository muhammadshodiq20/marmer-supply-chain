<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\IkmProfile;
use App\Models\ProductionStage;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        $ikmProfiles = IkmProfile::all();
        $selectedIkmId = $request->get('ikm', $ikmProfiles->first()?->id);
        $selectedIkm = $ikmProfiles->where('id', $selectedIkmId)->first() ?? $ikmProfiles->first();

        // Pesanan aktif di lantai produksi (belum shipped)
        $activeOrders = Order::with(['ikmProfile', 'productionStages'])
            ->where('ikm_profile_id', $selectedIkm->id)
            ->where('status', '!=', 'shipped')
            ->orderBy('tanggal_order', 'asc')
            ->get();

        return view('operator.index', compact('ikmProfiles', 'selectedIkm', 'activeOrders'));
    }

    public function quickAdvance(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'operator' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $stageMap = [
            'order_received' => 'penerimaan_order',
            'cutting' => 'pemotongan',
            'bubut_gerinda' => 'pembubutan',
            'resin_polishing' => 'penghalusan',
            'qc_pertama' => 'qc_pertama',
            'finishing' => 'poles_finishing',
            'qc_kedua' => 'qc_kedua',
            'packing' => 'packing',
            'ready_to_ship' => 'pengiriman',
            'shipped' => 'pengiriman',
        ];

        $order->update(['status' => $request->status]);

        $tahap = $stageMap[$request->status] ?? null;
        if ($tahap) {
            ProductionStage::create([
                'order_id' => $order->id,
                'tahap' => $tahap,
                'status' => 'in_progress',
                'waktu_mulai' => now(),
                'operator' => $request->input('operator', 'Operator Bengkel'),
                'catatan' => $request->input('catatan', 'Diperbarui via Shopfloor Quick Update'),
            ]);
        }

        if ($request->status === 'shipped') {
            $order->update(['tanggal_selesai' => now()]);
        }

        return back()->with('success', "Progres pesanan {$order->kode_order} berhasil dimajukan ke: " . $order->status_label);
    }
}
