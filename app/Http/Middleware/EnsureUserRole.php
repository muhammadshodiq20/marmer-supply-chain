<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = auth()->user();

        // Cek jika akun nonaktif
        if (isset($user->is_active) && !$user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh Administrator.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        // Cek apakah user memiliki salah satu role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak diizinkan, arahkan sesuai role yang tepat dengan pesan peringatan
        return match($user->role) {
            'operator' => redirect()->route('operator.index')->with('error', 'Akses dibatasi. Anda berada di portal Operator Bengkel.'),
            'buyer' => redirect()->route('catalog')->with('error', 'Akses dibatasi. Anda tidak memiliki izin untuk halaman internal IKM.'),
            default => redirect()->route('dashboard')->with('error', 'Akses tidak diizinkan.'),
        };
    }
}
