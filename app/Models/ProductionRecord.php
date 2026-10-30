<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'ikm_profile_id', 'product_id', 'tahun', 'bulan',
        'jenis_produk', 'jumlah_produksi', 'catatan',
    ];

    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
