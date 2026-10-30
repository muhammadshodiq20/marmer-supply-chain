<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteUtilization extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_record_id', 'ikm_profile_id', 'jenis_limbah', 'volume_estimasi',
        'pemanfaatan', 'status', 'harga_jual', 'pembeli', 'catatan',
    ];

    protected $casts = ['harga_jual' => 'decimal:2'];

    public function wasteRecord(): BelongsTo { return $this->belongsTo(WasteRecord::class); }
    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
}
