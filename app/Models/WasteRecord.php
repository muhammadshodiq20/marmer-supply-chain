<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WasteRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'ikm_profile_id', 'tanggal', 'aktivitas_diamati',
        'waktu_mulai', 'waktu_selesai', 'jenis_waste', 'catatan',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
    public function wasteUtilizations(): HasMany { return $this->hasMany(WasteUtilization::class); }
}
