<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionRecordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductionStageController;
use App\Http\Controllers\ProcessFlowController;
use App\Http\Controllers\WasteRecordController;
use App\Http\Controllers\WasteUtilizationController;
use App\Http\Controllers\SystemNeedController;
use App\Http\Controllers\IkmProfileController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BuyerProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Supply Chain IKM Marmer Tulungagung
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC ROUTES (Dapat Diakses Bebas Tanpa Login)
// ==========================================
Route::get('/', [DashboardController::class, 'landing'])->name('landing');
Route::get('/katalog', [ProductController::class, 'catalog'])->name('catalog');
Route::get('/lacak-pesanan', [TrackingController::class, 'index'])->name('tracking.index');

// Autentikasi Pengguna
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth & Account Selector
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::post('/auth/google/select', [AuthController::class, 'selectGoogleAccount'])->name('auth.google.select');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Quick Demo Login (Admin, Operator, Buyer)
Route::get('/auth/quick/{role}', [AuthController::class, 'quickLogin'])->name('auth.quick');

// Role Switcher (Simulasi untuk Pengujian & Demo Magang)
Route::get('/set-role/{role}', [RoleController::class, 'switch'])->name('role.switch');

// ==========================================
// 2. BUYER & AUTHENTICATED ROUTES
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Alur A: Pembelian Produk Siap Kirim dari Katalog (Checkout Langsung)
    Route::get('/katalog/{product}/checkout', [CheckoutController::class, 'show'])->name('catalog.checkout');
    Route::post('/katalog/{product}/checkout', [CheckoutController::class, 'process'])->name('catalog.checkout.process');
    Route::get('/pesanan/{order}/pembayaran', [CheckoutController::class, 'showPayment'])->name('pembayaran.show');
    Route::post('/pesanan/{order}/pembayaran', [CheckoutController::class, 'submitPaymentProof'])->name('pembayaran.submitProof');

    // Alur B: Pemesanan Custom Marmer (Make-to-Order Request)
    Route::get('/pesan-kustom', [OrderController::class, 'publicCreate'])->name('pesanan.public_create');
    Route::post('/pesan-kustom', [OrderController::class, 'publicStore'])->name('pesanan.public_store');

    // Profil & Pengaturan Akun Buyer (Poin 9 & Poin 3)
    Route::get('/profil-saya', [BuyerProfileController::class, 'show'])->name('buyer.profile');
    Route::get('/pesanan-saya/{order}', [BuyerProfileController::class, 'showOrder'])->name('buyer.orders.show');
    Route::put('/profil-saya', [BuyerProfileController::class, 'update'])->name('buyer.profile.update');

    // Sistem Notifikasi Interaktif (Poin 14)
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/api/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/api/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});

// ==========================================
// 3. OPERATOR BENGKEL ROUTES (Role: operator & admin)
// ==========================================
Route::middleware(['auth', 'role:operator,admin'])->group(function () {
    Route::get('/operator-bengkel', [OperatorController::class, 'index'])->name('operator.index');
    Route::post('/operator-bengkel/{order}/advance', [OperatorController::class, 'quickAdvance'])->name('operator.quickAdvance');
    
    // Alur Proses & SOP VSM
    Route::get('/alur-proses', [ProcessFlowController::class, 'index'])->name('alur-proses.index');
    Route::get('/alur-proses/{ikmProfile}', [ProcessFlowController::class, 'show'])->name('alur-proses.show');

    // SPK & Timeline Tahapan
    Route::get('/pesanan/{order}/cetak-spk', [OrderController::class, 'printSpk'])->name('pesanan.printSpk');
    Route::get('/pesanan/{order}/timeline', [OrderController::class, 'timeline'])->name('pesanan.timeline');
    Route::post('/tahap-produksi', [ProductionStageController::class, 'store'])->name('tahap-produksi.store');
    Route::patch('/tahap-produksi/{productionStage}', [ProductionStageController::class, 'update'])->name('tahap-produksi.update');

    // Verifikasi Pembayaran Bukti Transfer Masuk
    Route::post('/pesanan/{order}/verifikasi-pembayaran', [CheckoutController::class, 'verifyPayment'])->name('pesanan.verifyPayment');
});

// ==========================================
// 4. MANAGEMENT & EXECUTIVE ROUTES (Role: admin & operator)
// ==========================================
Route::middleware(['auth', 'role:admin,operator'])->group(function () {
    // Inventory Bahan Baku Balok Marmer (Pilar 1 - Wajib Login)
    Route::resource('bahan-baku', RawMaterialController::class)->parameters(['bahan-baku' => 'rawMaterial']);

    // Pemanfaatan Limbah & Circular Economy (Pilar 4 - Wajib Login, Full CRUD)
    Route::resource('pemanfaatan-limbah', WasteUtilizationController::class)->parameters(['pemanfaatan-limbah' => 'wasteUtilization']);

    // Manajemen Limbah Bengkel (Observasi 7 Waste)
    Route::resource('limbah', WasteRecordController::class)->parameters(['limbah' => 'wasteRecord']);
    Route::get('/limbah-analytics', [WasteRecordController::class, 'analytics'])->name('limbah.analytics');

    // Pesanan & Rekap Produksi
    Route::resource('pesanan', OrderController::class)->parameters(['pesanan' => 'order']);
    Route::patch('/pesanan/{order}/status', [OrderController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::resource('produksi', ProductionRecordController::class)->parameters(['produksi' => 'productionRecord']);
    Route::get('/produksi-chart', [ProductionRecordController::class, 'chart'])->name('produksi.chart');

    // Profil IKM (Poin 5 - Verifikasi Login Diperlukan)
    Route::resource('ikm', IkmProfileController::class)->parameters(['ikm' => 'ikmProfile']);

    // Katalog Master Produk Internal
    Route::resource('produk', ProductController::class)->parameters(['produk' => 'product']);

    // System Requirements & Matriks Transformasi (Poin 12)
    Route::get('/kebutuhan-sistem', [SystemNeedController::class, 'index'])->name('kebutuhan-sistem.index');
});

// ==========================================
// 5. EXCLUSIVE ADMIN ONLY ROUTES (Role: admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Executive Dashboard (Poin 1 & 3)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pengguna & Karyawan (Poin 10)
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Laporan & Ekspor Data Excel/PDF (Poin 13)
    Route::get('/laporan', [ReportExportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [ReportExportController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/export-pdf', [ReportExportController::class, 'exportPdf'])->name('laporan.pdf');
});
