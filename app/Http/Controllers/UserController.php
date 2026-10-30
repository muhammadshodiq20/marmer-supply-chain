<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\ActivityLog;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('telepon', 'like', "%$s%");
            });
        }

        $users = $query->latest()->paginate(15)->appends($request->query());
        $roleCounts = [
            'admin' => User::where('role', 'admin')->count(),
            'operator' => User::where('role', 'operator')->count(),
            'buyer' => User::where('role', 'buyer')->count(),
            'total' => User::count(),
        ];

        return view('users.index', compact('users', 'roleCounts'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,operator,buyer',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => ['required', Password::min(6)],
            'is_active' => 'nullable|boolean',
        ]);

        $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']) . '&background=' . ($validated['role'] === 'admin' ? '0d9488' : ($validated['role'] === 'operator' ? 'f59e0b' : '0284c7')) . '&color=fff';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'telepon' => $validated['telepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'is_active' => $request->has('is_active'),
            'password' => Hash::make($validated['password']),
            'avatar' => $avatar,
        ]);

        ActivityLog::log('Tambah Pengguna Internal', "Admin " . auth()->user()->name . " menambahkan akun {$user->name} ({$user->email}) sebagai {$user->role_label}.");

        return redirect()->route('users.index')->with('success', "Akun {$user->name} ({$user->role_label}) berhasil ditambahkan!");
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,operator,buyer',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = [Password::min(6)];
        }

        $validated = $request->validate($rules);

        // Jangan izinkan admin utama menurunkan rolenya sendiri menjadi non-admin jika cuma ada 1 admin
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri yang sedang aktif.');
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'telepon' => $validated['telepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        ActivityLog::log('Perbarui Akun Pengguna', "Admin " . auth()->user()->name . " memperbarui data akun {$user->name} ({$user->email}).");

        return redirect()->route('users.index')->with('success', "Data akun {$user->name} berhasil diperbarui.");
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::log('Ubah Status Akun', "Admin mengubah status akun {$user->name} menjadi {$statusStr}.");

        return back()->with('success', "Akun {$user->name} berhasil {$statusStr}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Akun admin utama ID 1 tidak boleh dihapus
        if ($user->id === 1) {
            return back()->with('error', 'Akun Administrator Utama tidak dapat dihapus demi keamanan sistem.');
        }

        $userName = $user->name;
        $userRole = $user->role_label;
        $user->delete();

        ActivityLog::log('Hapus Akun Pengguna', "Admin menghapus akun {$userName} ({$userRole}).");

        return redirect()->route('users.index')->with('success', "Akun {$userName} berhasil dihapus dari sistem.");
    }
}
