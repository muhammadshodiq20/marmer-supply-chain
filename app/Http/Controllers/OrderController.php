<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\IkmProfile;
use App\Models\ProductionStage;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['ikmProfile', 'user']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('status_pembayaran')) $query->where('status_pembayaran', $request->status_pembayaran);
        if ($request->filled('tipe_pesanan')) $query->where('tipe_pesanan', $request->tipe_pesanan);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('kode_order', 'like', "%$s%")->orWhere('nama_pelanggan', 'like', "%$s%"));
        }
        if ($request->filled('ikm')) $query->where('ikm_profile_id', $request->ikm);

        $orders = $query->latest()->paginate(15)->appends($request->query());
        $ikmProfiles = IkmProfile::all();
        $statusCounts = Order::select('status', \DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');

        return view('pesanan.index', compact('orders', 'ikmProfiles', 'statusCounts'));
    }

    public function create()
    {
        $ikmProfiles = IkmProfile::all();
        return view('pesanan.create', compact('ikmProfiles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'nama_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'nullable|string',
            'alamat_pengiriman' => 'nullable|string',
            'jenis_produk' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'spesifikasi' => 'nullable|string',
            'tanggal_order' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        $validated['status'] = 'order_received';
        $validated['total_harga'] = $validated['jumlah'] * 450000;
        $order = Order::create($validated);
        
        ProductionStage::create([
            'order_id' => $order->id,
            'tahap' => 'order_received',
            'status' => 'completed',
            'waktu_mulai' => now(),
            'waktu_selesai' => now(),
            'operator' => auth()->user()->name,
            'catatan' => 'Pesanan baru dimasukkan ke antrean produksi oleh Admin',
        ]);

        return redirect()->route('pesanan.show', $order)->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(Order $order)
    {
        $order->load(['ikmProfile', 'productionStages' => fn($q) => $q->latest(), 'user', 'product']);
        return view('pesanan.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $ikmProfiles = IkmProfile::all();
        return view('pesanan.edit', compact('order', 'ikmProfiles'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'nama_pelanggan' => 'required|string|max:255',
            'telepon_pelanggan' => 'nullable|string',
            'alamat_pengiriman' => 'nullable|string',
            'jenis_produk' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'nullable|numeric',
            'spesifikasi' => 'nullable|string',
            'status' => 'required|string',
            'status_pembayaran' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $order->update($validated);
        return redirect()->route('pesanan.show', $order)->with('success', 'Data pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $kode = $order->kode_order;
        $order->delete();
        return redirect()->route('pesanan.index')->with('success', "Pesanan {$kode} berhasil dihapus.");
    }

    /**
     * Update Status Tahapan Produksi / Selesaikan Pesanan (Sinkron Real-Time dengan Buyer)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'operator' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $statusLama = $order->status_label;
        $newStatus = $request->status;

        $updatePayload = ['status' => $newStatus];

        // Jika Admin menandai pesanan selesai
        if ($newStatus === 'completed' || $newStatus === 'shipped') {
            $updatePayload['tanggal_selesai'] = now();
        }

        $order->update($updatePayload);

        // Catat di timeline tahapan produksi
        ProductionStage::create([
            'order_id' => $order->id,
            'tahap' => $newStatus,
            'status' => 'completed',
            'waktu_mulai' => now(),
            'waktu_selesai' => now(),
            'operator' => $request->input('operator', auth()->user()->name),
            'catatan' => $request->input('catatan', "Status diperbarui ke: {$order->status_label}"),
        ]);

        // =========================================================================
        // SINKRONISASI NOTIFIKASI OTOMATIS KE DASHBOARD BUYER (Poin 2 & 4)
        // =========================================================================
        if ($order->user_id) {
            $judul = ($newStatus === 'completed') 
                ? "Pesanan Anda Telah Selesai! ({$order->kode_order})" 
                : "Pembaruan Status Pesanan ({$order->kode_order})";

            $pesan = ($newStatus === 'completed')
                ? "Pesanan {$order->jenis_produk} ({$order->jumlah} unit) telah dinyatakan SELESAI oleh Admin. Terima kasih telah mempercayakan kerajinan marmer Anda pada sentra Tulungagung!"
                : "Status pengerjaan pesanan Anda ({$order->kode_order}) telah diperbarui dari '{$statusLama}' menjadi '{$order->status_label}'.";

            Notification::create([
                'user_id' => $order->user_id,
                'judul' => $judul,
                'pesan' => $pesan,
                'tipe' => ($newStatus === 'completed' ? 'success' : 'order_status'),
                'link' => route('buyer.orders.show', $order),
                'is_read' => false,
            ]);
        }

        ActivityLog::log('Pembaruan Status Pesanan', "Admin mengubah status {$order->kode_order} menjadi {$order->status_label}");

        return redirect()->route('pesanan.show', $order)->with('success', "Status pesanan berhasil diperbarui ke: {$order->status_label}. Informasi telah tersinkronisasi ke akun Buyer!");
    }

    public function printSpk(Order $order)
    {
        $order->load(['ikmProfile', 'productionStages']);
        return view('pesanan.spk', compact('order'));
    }

    public function publicCreate()
    {
        $ikmProfiles = IkmProfile::all();
        return view('pesanan.public_create', compact('ikmProfiles'));
    }

    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'ikm_profile_id' => 'required|exists:ikm_profiles,id',
            'jenis_produk' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'alamat_pengiriman' => 'required|string',
            'spesifikasi' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $user = auth()->user();
        $validated['user_id'] = $user?->id;
        $validated['tipe_pesanan'] = 'kustom';
        $validated['nama_pelanggan'] = $user?->name ?? $request->input('nama_pelanggan', 'Pelanggan Online');
        $validated['telepon_pelanggan'] = $user?->telepon ?? $request->input('telepon_pelanggan', '-');
        $validated['tanggal_order'] = now()->toDateString();
        $validated['status'] = 'order_received';
        $validated['status_pembayaran'] = 'menunggu_pembayaran';
        $validated['total_harga'] = $validated['jumlah'] * 450000;

        $order = Order::create($validated);

        ProductionStage::create([
            'order_id' => $order->id,
            'tahap' => 'penerimaan_order',
            'status' => 'completed',
            'waktu_mulai' => now(),
            'waktu_selesai' => now(),
            'operator' => 'Sistem Online Buyer',
            'catatan' => 'Pesanan kustom dibuat oleh Buyer (' . ($user?->name ?? 'Pelanggan') . ')',
        ]);

        Notification::create([
            'judul' => "Pesanan Kustom Masuk ({$order->kode_order})",
            'pesan' => "Pesanan kustom {$order->jenis_produk} diajukan oleh {$order->nama_pelanggan}.",
            'tipe' => 'order',
            'link' => route('pesanan.show', $order),
            'is_read' => false,
        ]);

        ActivityLog::log('Pesanan Kustom Baru', "Buyer {$order->nama_pelanggan} membuat pesanan kustom {$order->kode_order}");

        return redirect()->route('buyer.orders.show', $order)
            ->with('success', "Pesanan kustom Anda berhasil dibuat! Kode Order Anda: {$order->kode_order}. Silakan pantau status pengerjaan secara real-time.");
    }

    public function timeline(Order $order)
    {
        $stages = $order->productionStages()->orderBy('created_at')->get();
        return response()->json($stages);
    }
}
