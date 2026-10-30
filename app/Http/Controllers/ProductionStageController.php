<?php

namespace App\Http\Controllers;

use App\Models\ProductionStage;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductionStageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'tahap' => 'required|string',
            'status' => 'required|string',
            'operator' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $stage = ProductionStage::create([
            'order_id' => $validated['order_id'],
            'tahap' => $validated['tahap'],
            'status' => $validated['status'],
            'waktu_mulai' => now(),
            'operator' => $validated['operator'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return back()->with('success', 'Tahapan produksi berhasil diperbarui.');
    }

    public function update(Request $request, ProductionStage $productionStage)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'operator' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed' && !$productionStage->waktu_selesai) {
            $validated['waktu_selesai'] = now();
        }

        $productionStage->update($validated);

        return back()->with('success', 'Status tahap produksi berhasil diperbarui.');
    }
}
