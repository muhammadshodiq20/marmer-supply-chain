<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'peran',
        'aktivitas',
        'deskripsi',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $aktivitas, ?string $deskripsi = null): self
    {
        $user = auth()->user();
        return self::create([
            'user_id' => $user?->id,
            'nama_pengguna' => $user?->name ?? 'Tamu / Publik',
            'peran' => $user?->role ?? 'tamu',
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
        ]);
    }
}
