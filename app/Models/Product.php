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

    protected $appends = ['image_url'];

    public function ikmProfile(): BelongsTo { return $this->belongsTo(IkmProfile::class); }
    public function productionRecords(): HasMany { return $this->hasMany(ProductionRecord::class); }
    public function galleries(): HasMany { return $this->hasMany(ProductGallery::class); }

    /**
     * Get image URL with automatic fallback to high-quality authentic marble photo
     */
    public function getImageUrlAttribute(): string
    {
        if (!empty($this->gambar_path)) {
            if (str_starts_with($this->gambar_path, 'http://') || str_starts_with($this->gambar_path, 'https://')) {
                return $this->gambar_path;
            }
            $baseName = basename($this->gambar_path);
            if (file_exists(public_path('images/products/' . $baseName))) {
                return asset('images/products/' . $baseName);
            }
            if (file_exists(public_path('storage/' . $this->gambar_path))) {
                return asset('storage/' . $this->gambar_path);
            }
        }

        // Default high-quality authentic photos for Tulungagung marble & stone crafts
        $name = strtolower($this->nama_produk ?? '');
        $stone = strtolower($this->jenis_batu ?? '');

        if (str_contains($name, 'wastafel') || str_contains($name, 'sink')) {
            if (str_contains($name, 'kali') || str_contains($stone, 'kali') || str_contains($stone, 'boulder')) {
                return 'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?auto=format&fit=crop&w=800&q=80';
            }
            return 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($name, 'pedestal')) {
            return 'https://images.unsplash.com/photo-1620626011761-996317b8d101?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($name, 'dispenser') || str_contains($name, 'sabun')) {
            return 'https://images.unsplash.com/photo-1608248597359-009772a0833d?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($name, 'stepping') || str_contains($name, 'pijakan')) {
            return 'https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($name, 'lampu') || str_contains($name, 'lampion')) {
            return 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($name, 'meja') || str_contains($name, 'kursi') || str_contains($name, 'onix')) {
            return 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=800&q=80';
        }

        return 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80';
    }
}
