<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'ikm_profile_id', 'nomor_urut', 'tahap_proses', 'aktor_terlibat',
        'informasi_seharusnya', 'kondisi_saat_ini', 'gap_teridentifikasi',
        'jenis_waste', 'dampak', 'jenis_produk_konteks',
    ];

    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
}
