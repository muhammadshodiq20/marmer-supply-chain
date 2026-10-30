<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\ActivityLog;
use App\Models\Order;

class BuyerProfileController extends Controller
{
    /**
     * Halaman Dashboard Profil Buyer
     */
    public function show(Request $request)
    {
        $user = auth()->user();

        // Ambil SEMUA pesanan milik buyer (tanpa terhapus walaupun sudah berstatus selesai)
        $ordersQuery = Order::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('telepon_pelanggan', $user->telepon)
              ->orWhere('nama_pelanggan', $user->name);
        })->with(['ikmProfile', 'product']);

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'menunggu_bayar') {
                $ordersQuery->whereIn('status', ['waiting_payment', 'order_received']);
            } elseif ($status === 'menunggu_verifikasi') {
                $ordersQuery->where('status_pembayaran', 'menunggu_verifikasi');
            } elseif ($status === 'diproses') {
                $ordersQuery->whereIn('status', ['cutting', 'bubut_gerinda', 'resin_polishing', 'qc_pertama', 'finishing', 'qc_kedua', 'packing']);
            } elseif ($status === 'dikirim') {
                $ordersQuery->whereIn('status', ['ready_to_ship', 'shipped']);
            } elseif ($status === 'selesai') {
                $ordersQuery->where('status', 'completed');
            }
        }

        $myOrders = $ordersQuery->latest()->get();

        // Hitung statistik untuk filter badge
        $allOrders = Order::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('telepon_pelanggan', $user->telepon)
              ->orWhere('nama_pelanggan', $user->name);
        })->get();

        $counts = [
            'total' => $allOrders->count(),
            'menunggu_bayar' => $allOrders->whereIn('status', ['waiting_payment', 'order_received'])->count(),
            'menunggu_verifikasi' => $allOrders->where('status_pembayaran', 'menunggu_verifikasi')->count(),
            'diproses' => $allOrders->whereIn('status', ['cutting', 'bubut_gerinda', 'resin_polishing', 'qc_pertama', 'finishing', 'qc_kedua', 'packing'])->count(),
            'dikirim' => $allOrders->whereIn('status', ['ready_to_ship', 'shipped'])->count(),
            'selesai' => $allOrders->where('status', 'completed')->count(),
        ];

        return view('buyer.profile', compact('user', 'myOrders', 'counts'));
    }

    /**
     * Halaman Rincian Pesanan Khusus Buyer
     */
    public function showOrder(Order $order)
    {
        $user = auth()->user();

        // Pastikan hanya pemilik pesanan atau admin yang dapat membuka
        if ($order->user_id !== $user->id && $order->telepon_pelanggan !== $user->telepon && !$user->isAdmin()) {
            return redirect()->route('buyer.profile')->with('error', 'Anda tidak memiliki hak akses untuk pesanan ini.');
        }

        $order->load(['ikmProfile', 'product', 'productionStages' => fn($q) => $q->latest()]);
        return view('buyer.order_detail', compact('order'));
    }

    /**
     * Update Data Profil & Alamat
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:1000',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:10240',
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'telepon.required' => 'Nomor WhatsApp / telepon wajib diisi untuk koordinasi pengiriman marmer.',
            'alamat.required' => 'Alamat pengiriman lengkap wajib diisi.',
            'avatar.max' => 'Ukuran foto profil maksimal 10 MB.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'telepon' => $validated['telepon'],
            'alamat' => $validated['alamat'],
        ];

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            $updateData['avatar'] = asset('storage/' . $path);
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        ActivityLog::log('Perbarui Profil Pribadi', "Pengguna {$user->name} memperbarui data profil akun.");

        return back()->with('success', 'Profil dan alamat pengiriman Anda berhasil diperbarui! Data ini akan otomatis digunakan untuk pesanan Anda berikutnya.');
    }
}
