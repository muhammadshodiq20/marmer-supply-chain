<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'tahap', 'status', 'waktu_mulai', 'waktu_selesai', 'operator', 'catatan',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public const STAGE_LABELS = [
        'penerimaan_order' => 'Penerimaan Order',
        'pemotongan' => 'Pemotongan',
        'pembubutan' => 'Pembubutan & Gerinda',
        'penghalusan' => 'Resin & Penghalusan',
        'qc_pertama' => 'QC Pertama',
        'poles_finishing' => 'Poles & Finishing',
        'qc_kedua' => 'QC Kedua',
        'packing' => 'Packing',
        'pengiriman' => 'Pengiriman',
    ];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
