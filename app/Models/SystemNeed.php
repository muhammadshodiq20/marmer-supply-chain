<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemNeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'ikm_profile_id', 'tanggal', 'aktivitas', 'waktu', 'alat_sistem', 'masalah_kendala',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
}
