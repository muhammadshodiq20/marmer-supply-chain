<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IkmProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pemilik', 'nama_usaha', 'fokus_produksi', 'lokasi',
        'kekuatan_produksi', 'deskripsi', 'logo_path', 'telepon', 'email',
    ];

    public function rawMaterials(): HasMany { return $this->hasMany(RawMaterial::class); }
    public function products(): HasMany { return $this->hasMany(Product::class); }
    public function productionRecords(): HasMany { return $this->hasMany(ProductionRecord::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function processFlows(): HasMany { return $this->hasMany(ProcessFlow::class); }
    public function wasteRecords(): HasMany { return $this->hasMany(WasteRecord::class); }
    public function wasteUtilizations(): HasMany { return $this->hasMany(WasteUtilization::class); }
    public function systemNeeds(): HasMany { return $this->hasMany(SystemNeed::class); }
}
