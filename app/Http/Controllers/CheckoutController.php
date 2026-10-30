<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductionStage;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Tampilkan Halaman Checkout Langsung untuk Produk Katalog
     */
    public function show(Product $product)
    {
        $user = auth()->user();
        $product->load('ikmProfile');

        return view('catalog.checkout', compact('product', 'user'));
    }

    /**
     * Proses Pembuatan Pesanan Pembelian Katalog
     */
    public function process(Request $request, Product $product)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'alamat_pengiriman' => 'required|string|max:1000',
            'telepon_pelanggan' => 'required|string|max:25',
            'metode_pembayaran' => 'required|in:transfer_bca,transfer_mandiri,transfer_bri,cod',
            'catatan' => 'nullable|string',
        ], [
            'jumlah.required' => 'Jumlah pembelian wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1 pcs.',
            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',
            'telepon_pelanggan.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'metode_pembayaran.required' => 'Pilih salah satu metode pembayaran.',
        ]);

        $user = auth()->user();
        $hargaSatuan = $product->harga ?? 450000;
        $biayaPengiriman = 75000; // Standar packing peti kayu & ekspedisi Jawa-Bali
        $totalHarga = ($hargaSatuan * $validated['jumlah']) + $biayaPengiriman;

        $order = Order::create([
            'ikm_profile_id' => $product->ikm_profile_id,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'tipe_pesanan' => 'katalog',
            'nama_pelanggan' => $user->name,
            'telepon_pelanggan' => $validated['telepon_pelanggan'],
            'alamat_pengiriman' => $validated['alamat_pengiriman'],
            'jenis_produk' => $product->nama_produk,
            'jumlah' => $validated['jumlah'],
            'harga_satuan' => $hargaSatuan,
            'biaya_pengiriman' => $biayaPengiriman,
            'total_harga' => $totalHarga,
            'spesifikasi' => "Pembelian Produk Katalog Siap Kirim: {$product->nama_produk} ({$product->jenis_batu})",
            'status' => 'waiting_payment',
            'status_pembayaran' => 'menunggu_pembayaran',
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'tanggal_order' => now()->toDateString(),
            'catatan' => $validated['catatan'],
        ]);

        // Catat milestone awal
        ProductionStage::create([
            'order_id' => $order->id,
            'tahap' => 'checkout_katalog',
            'status' => 'completed',
            'waktu_mulai' => now(),
            'waktu_selesai' => now(),
            'operator' => 'Sistem Toko Online',
            'catatan' => "Pesanan dibuat langsung dari Katalog oleh {$user->name}",
        ]);

        // Kirim Notifikasi Sistem ke Admin
        Notification::create([
            'judul' => "Pesanan Katalog Baru ({$order->kode_order})",
            'pesan' => "{$user->name} memesan {$product->nama_produk} sebanyak {$order->jumlah} pcs (Total: Rp " . number_format($totalHarga, 0, ',', '.') . ").",
            'tipe' => 'order',
            'link' => route('pesanan.show', $order),
            'is_read' => false,
        ]);

        ActivityLog::log('Checkout Produk Katalog', "Buyer {$user->name} membuat pesanan katalog {$order->kode_order} ({$product->nama_produk})");

        return redirect()->route('pembayaran.show', $order)
            ->with('success', "Pesanan Anda berhasil dibuat! Silakan lakukan konfirmasi pembayaran untuk memproses pesanan.");
    }

    /**
     * Halaman Konfirmasi & Upload Bukti Pembayaran
     */
    public function showPayment(Order $order)
    {
        $order->load(['ikmProfile', 'product', 'user']);
        return view('catalog.payment', compact('order'));
    }

    /**
     * Upload Bukti Transfer oleh Buyer
     */
    public function submitPaymentProof(Request $request, Order $order)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'catatan_bayar' => 'nullable|string',
        ], [
            'bukti_pembayaran.required' => 'Silakan pilih foto struk / tangkapan layar bukti transfer.',
            'bukti_pembayaran.max' => 'Ukuran foto maksimal 10 MB.',
        ]);

        $file = $request->file('bukti_pembayaran');
        $filename = 'bukti_' . $order->kode_order . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payments', $filename, 'public');

        $order->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
            'status' => 'payment_pending_verification',
            'tanggal_pembayaran' => now(),
            'catatan' => $order->catatan . ($request->filled('catatan_bayar') ? " | Info Bayar: " . $request->catatan_bayar : ''),
        ]);

        // Notifikasi ke Admin
        Notification::create([
            'judul' => "Bukti Pembayaran Masuk ({$order->kode_order})",
            'pesan' => "Buyer {$order->nama_pelanggan} telah mengunggah bukti pembayaran sebesar Rp " . number_format($order->total_harga, 0, ',', '.') . ". Menunggu verifikasi Admin.",
            'tipe' => 'payment',
            'link' => route('pesanan.show', $order),
            'is_read' => false,
        ]);

        ActivityLog::log('Unggah Bukti Bayar', "Buyer {$order->nama_pelanggan} mengunggah bukti transfer untuk pesanan {$order->kode_order}");

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Pihak Admin/Operator akan segera memverifikasi pembayaran Anda.');
    }

    /**
     * Verifikasi Pembayaran oleh Admin / Operator
     */
    public function verifyPayment(Request $request, Order $order)
    {
        $action = $request->input('action', 'approve'); // 'approve' atau 'reject'
        $adminUser = auth()->user();

        if ($action === 'approve') {
            $order->update([
                'status_pembayaran' => 'lunas',
                'status' => 'payment_verified',
                'diverifikasi_oleh' => $adminUser->name . ' (' . $adminUser->role_label . ')',
                'tanggal_verifikasi' => now(),
                'nominal_verifikasi' => $order->total_harga,
                'catatan_verifikasi' => 'Pembayaran pelunasan telah diverifikasi sesuai mutasi rekening oleh ' . $adminUser->name,
            ]);

            ProductionStage::create([
                'order_id' => $order->id,
                'tahap' => 'verifikasi_pembayaran',
                'status' => 'completed',
                'waktu_mulai' => now(),
                'waktu_selesai' => now(),
                'operator' => $adminUser->name,
                'catatan' => 'Pembayaran pelunasan sebesar Rp ' . number_format($order->total_harga, 0, ',', '.') . ' telah diverifikasi lunas.',
            ]);

            // =========================================================================
            // NOTIFIKASI OTOMATIS KE BUYER (Poin 1: Pesanan, Nominal, Tanggal, Status)
            // =========================================================================
            if ($order->user_id) {
                $tanggalVerif = now()->translatedFormat('d F Y H:i');
                $nominalFmt = 'Rp ' . number_format($order->total_harga, 0, ',', '.');

                Notification::create([
                    'user_id' => $order->user_id,
                    'judul' => "Pembayaran Pelunasan Terverifikasi ({$order->kode_order})",
                    'pesan' => "Pembayaran pelunasan pesanan Anda ({$order->kode_order}) sebesar {$nominalFmt} telah berhasil diverifikasi oleh Admin pada {$tanggalVerif} WIB. Status pembayaran kini LUNAS dan pesanan Anda diproses ke tahap berikutnya.",
                    'tipe' => 'payment_success',
                    'link' => route('buyer.orders.show', $order),
                    'is_read' => false,
                ]);
            }

            ActivityLog::log('Verifikasi Pembayaran', "Admin {$adminUser->name} memverifikasi pembayaran lunas untuk pesanan {$order->kode_order} ({$order->jenis_produk})");

            return back()->with('success', "Pembayaran untuk pesanan {$order->kode_order} BERHASIL DIVERIFIKASI (LUNAS)! Notifikasi otomatis telah dikirimkan ke akun Buyer.");
        } else {
            $order->update([
                'status_pembayaran' => 'ditolak',
                'status' => 'waiting_payment',
                'diverifikasi_oleh' => $adminUser->name,
                'tanggal_verifikasi' => now(),
                'catatan_verifikasi' => 'Bukti pembayaran ditolak: ' . $request->input('alasan', 'Bukti transfer tidak terbaca / dana belum masuk'),
            ]);

            if ($order->user_id) {
                Notification::create([
                    'user_id' => $order->user_id,
                    'judul' => "Pembayaran Ditolak ({$order->kode_order})",
                    'pesan' => "Bukti transfer pesanan {$order->kode_order} belum dapat diverifikasi oleh Admin. Silakan periksa mutasi atau unggah ulang bukti yang valid.",
                    'tipe' => 'alert',
                    'link' => route('pembayaran.show', $order),
                    'is_read' => false,
                ]);
            }

            ActivityLog::log('Tolak Pembayaran', "Admin menolak bukti pembayaran pesanan {$order->kode_order}");

            return back()->with('error', "Bukti pembayaran pesanan {$order->kode_order} ditolak. Notifikasi telah dikirim ke Buyer.");
        }
    }
}
