<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'ikm_profile_id', 'nama_produk', 'jenis_batu', 'deskripsi',
        'harga', 'gambar_path', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'harga' => 'decimal:2'];

    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
    public function productionRecords(): HasMany { return $this->hasMany(ProductionRecord::class); }
    public function galleries(): HasMany { return $this->hasMany(ProductGallery::class); }
}
