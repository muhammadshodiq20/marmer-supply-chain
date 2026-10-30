<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ikm_profile_id', 'user_id', 'product_id', 'tipe_pesanan', 'kode_order',
        'nama_pelanggan', 'telepon_pelanggan', 'alamat_pengiriman', 'jenis_produk',
        'jumlah', 'harga_satuan', 'biaya_pengiriman', 'total_harga', 'spesifikasi',
        'status', 'status_pembayaran', 'metode_pembayaran', 'bukti_pembayaran',
        'tanggal_pembayaran', 'diverifikasi_oleh', 'tanggal_verifikasi', 'nominal_verifikasi',
        'catatan_verifikasi', 'tanggal_order', 'tanggal_selesai', 'catatan',
    ];

    protected $casts = [
        'tanggal_order' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_pembayaran' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'harga_satuan' => 'decimal:2',
        'biaya_pengiriman' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'nominal_verifikasi' => 'decimal:2',
    ];

    public const STATUS_LABELS = [
        'order_received' => 'Pesanan Diterima',
        'waiting_payment' => 'Menunggu Pembayaran',
        'payment_pending_verification' => 'Menunggu Verifikasi Pembayaran',
        'payment_verified' => 'Pembayaran Berhasil / Terverifikasi',
        'cutting' => 'Pemotongan Balok / Slep',
        'bubut_gerinda' => 'Bubut & Gerinda',
        'resin_polishing' => 'Resin & Polishing',
        'qc_pertama' => 'QC Pertama',
        'finishing' => 'Finishing Hi-Glossy',
        'qc_kedua' => 'QC Kedua',
        'packing' => 'Packing Peti Kayu',
        'ready_to_ship' => 'Siap Kirim',
        'shipped' => 'Dalam Pengiriman',
        'completed' => 'Pesanan Selesai',
    ];

    public const STATUS_COLORS = [
        'order_received' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        'waiting_payment' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
        'payment_pending_verification' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
        'payment_verified' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
        'cutting' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
        'bubut_gerinda' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
        'resin_polishing' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300',
        'qc_pertama' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300',
        'finishing' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
        'qc_kedua' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300',
        'packing' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300',
        'ready_to_ship' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300',
        'shipped' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-200',
        'completed' => 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-200',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'bg-gray-100 text-gray-700';
    }

    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function productionStages(): HasMany { return $this->hasMany(ProductionStage::class); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->kode_order)) {
                $prefix = ($order->tipe_pesanan === 'katalog') ? 'CAT' : 'CUS';
                $order->kode_order = $prefix . '-' . date('Ymd') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
