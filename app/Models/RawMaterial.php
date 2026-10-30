<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'ikm_profile_id', 'tahun', 'bulan', 'jenis_batu',
        'jumlah_pembelian', 'dimensi', 'warna', 'status', 'catatan',
    ];

    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
}
