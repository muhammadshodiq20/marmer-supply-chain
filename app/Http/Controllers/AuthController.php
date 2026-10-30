<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Akun Anda dinonaktifkan oleh Administrator. Hubungi pihak IKM.']);
            }

            $request->session()->regenerate();
            session(['active_role' => $user->role]);

            ActivityLog::log('Login Pengguna', "User {$user->name} ({$user->email}) berhasil login sebagai {$user->role_label}.");

            return redirect()->intended($this->getRedirectUrl($user))
                ->with('success', "Selamat datang kembali, {$user->name}! Anda login sebagai {$user->role_label}.");
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.',
            'telepon.required' => 'Nomor WhatsApp wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']) . '&background=0d9488&color=fff';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'],
            'alamat' => $validated['alamat'] ?? null,
            'role' => 'buyer',
            'is_active' => true,
            'password' => Hash::make($validated['password']),
            'avatar' => $avatar,
        ]);

        Auth::login($user);
        session(['active_role' => $user->role]);

        ActivityLog::log('Pendaftaran Akun Baru', "Pengguna {$user->name} ({$user->email}) mendaftar mandiri sebagai Buyer.");

        return redirect()->route('catalog')
            ->with('success', "Pendaftaran berhasil! Selamat datang di Sentra Marmer Tulungagung, {$user->name}.");
    }

    public function logout(Request $request)
    {
        $userName = Auth::user()?->name;
        ActivityLog::log('Logout Pengguna', "User {$userName} telah logout dari sistem.");

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda telah berhasil keluar (logout).');
    }

    // =========================================================================
    // OAUTH GOOGLE RESMI & SELECTION UI
    // =========================================================================

    /**
     * Tampilkan Halaman Dialog Pemilihan Akun Google
     */
    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');

        // Jika Client ID riil sudah diisi di .env, arahkan ke Google Accounts live
        if (!empty($clientId) && $clientId !== 'null' && $clientId !== 'dummy') {
            return Socialite::driver('google')
                ->with(['prompt' => 'select_account'])
                ->redirect();
        }

        // Jika belum ada API key Google Console di .env, tampilkan UI Dialog Akun Google Asli
        // Pengguna bisa memilih akun atau mengetikkan nama & email Google miliknya sendiri
        return view('auth.google_select');
    }

    /**
     * Proses Pemilihan Akun Google dari Dialog Pemilih Akun
     */
    public function selectGoogleAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'avatar' => 'nullable|string',
        ]);

        $avatar = $validated['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']) . '&background=4285F4&color=fff';

        $user = User::where('email', $validated['email'])->first();

        if ($user) {
            $user->update([
                'avatar' => $avatar,
                'google_id' => 'g_' . md5($validated['email']),
            ]);
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'google_id' => 'g_' . md5($validated['email']),
                'role' => 'buyer',
                'avatar' => $avatar,
                'is_active' => true,
                'password' => Hash::make(uniqid('google_auth_')),
            ]);
        }

        if (!$user->is_active) {
            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan oleh Administrator.');
        }

        Auth::login($user, true);
        session(['active_role' => $user->role]);

        ActivityLog::log('Login Akun Google', "User {$user->name} ({$user->email}) berhasil login melalui Google Account Selector.");

        return redirect()->intended($this->getRedirectUrl($user))
            ->with('success', "Berhasil masuk dengan Akun Google: {$user->name} ({$user->email})!");
    }

    /**
     * Callback dari Google OAuth Live (Jika API Key Google Console diaktifkan)
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar() ?? $user->avatar,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role' => 'buyer',
                    'avatar' => $googleUser->getAvatar(),
                    'is_active' => true,
                    'password' => Hash::make(uniqid('g_auth_')),
                ]);
            }

            if (!$user->is_active) {
                return redirect()->route('login')->with('error', 'Akun Google Anda dinonaktifkan oleh Administrator.');
            }

            Auth::login($user, true);
            session(['active_role' => $user->role]);

            ActivityLog::log('Login Google OAuth Asli', "User {$user->name} ({$user->email}) berhasil login via Google OAuth.");

            return redirect()->intended($this->getRedirectUrl($user))
                ->with('success', "Berhasil masuk dengan akun Google: {$user->name} ({$user->email})!");

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal memproses autentikasi Google: ' . $e->getMessage());
        }
    }

    /**
     * 1-Click Quick Demo Login for Testing Roles (Admin, Operator, Buyer)
     */
    public function quickLogin(string $role)
    {
        $emailMap = [
            'admin' => 'admin@marmer.test',
            'operator' => 'operator@marmer.test',
            'buyer' => 'buyer@marmer.test',
        ];

        $email = $emailMap[$role] ?? 'admin@marmer.test';
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            session(['active_role' => $user->role]);
            ActivityLog::log('Demo Login Instan', "Login instan role: {$user->role_label}");
            return $this->redirectBasedOnRole($user)
                ->with('success', "Berhasil masuk instan sebagai {$user->role_label}!");
        }

        return redirect()->route('login');
    }

    private function getRedirectUrl(User $user): string
    {
        return match($user->role) {
            'operator' => route('operator.index'),
            'buyer' => route('catalog'),
            default => route('dashboard'),
        };
    }

    private function redirectBasedOnRole(User $user)
    {
        return redirect()->to($this->getRedirectUrl($user));
    }
}
