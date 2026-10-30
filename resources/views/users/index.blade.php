@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Karyawan')
@section('page-title', 'Manajemen Akun Internal & Karyawan')
@section('page-subtitle', 'Kelola Akun Admin, Operator Bengkel, dan Pelanggan Ekosistem')

@section('content')
<div class="space-y-6">

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 uppercase">Total Akun Terdaftar</span>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $roleCounts['total'] }} Akun</h3>
            <span class="text-[10px] text-gray-400">Seluruh entitas terdaftar</span>
        </div>
        <div class="glass p-5 rounded-3xl border border-teal-500/20 shadow-sm bg-teal-500/5">
            <span class="text-xs font-semibold text-teal-600 uppercase">Admin / Pemilik Usaha</span>
            <h3 class="text-2xl font-black text-teal-600 mt-1">{{ $roleCounts['admin'] }} Orang</h3>
            <span class="text-[10px] text-teal-500">Akses penuh sistem</span>
        </div>
        <div class="glass p-5 rounded-3xl border border-amber-500/20 shadow-sm bg-amber-500/5">
            <span class="text-xs font-semibold text-amber-600 uppercase">Operator Bengkel</span>
            <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $roleCounts['operator'] }} Orang</h3>
            <span class="text-[10px] text-amber-500">Lantai kerja (Shopfloor)</span>
        </div>
        <div class="glass p-5 rounded-3xl border border-cyan-500/20 shadow-sm bg-cyan-500/5">
            <span class="text-xs font-semibold text-cyan-600 uppercase">Pelanggan / Buyer</span>
            <h3 class="text-2xl font-black text-cyan-600 mt-1">{{ $roleCounts['buyer'] }} Orang</h3>
            <span class="text-[10px] text-cyan-500">Pemesanan & tracking</span>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <div class="glass p-6 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, no HP..." class="px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 w-64">
            
            <select name="role" class="px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="operator" {{ request('role') === 'operator' ? 'selected' : '' }}>Operator Bengkel</option>
                <option value="buyer" {{ request('role') === 'buyer' ? 'selected' : '' }}>Buyer</option>
            </select>

            <button type="submit" class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-bold shadow-sm">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'role']))
                <a href="{{ route('users.index') }}" class="text-xs text-gray-400 hover:underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('users.create') }}" class="px-5 py-2.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Tambah Akun Baru
        </a>
    </div>

    <!-- Users Table -->
    <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                        <th class="py-3 px-6 font-semibold">Pengguna</th>
                        <th class="py-3 px-6 font-semibold">Kontak & Alamat</th>
                        <th class="py-3 px-6 font-semibold">Peran (Role)</th>
                        <th class="py-3 px-6 font-semibold">Status Akun</th>
                        <th class="py-3 px-6 font-semibold">Terdaftar Pada</th>
                        <th class="py-3 px-6 font-semibold text-center">Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/60 text-gray-700 dark:text-gray-300">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $u->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name) }}" alt="{{ $u->name }}" class="w-9 h-9 rounded-full object-cover border border-teal-500/30">
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white">{{ $u->name }}</p>
                                        <span class="text-xs text-gray-400">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $u->telepon ?? '-' }}</p>
                                <p class="text-gray-400 truncate max-w-xs">{{ $u->alamat ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $u->role === 'admin' ? 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300' : ($u->role === 'operator' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300') }}">
                                    {{ $u->role_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <form action="{{ route('users.toggleStatus', $u) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Klik untuk ubah status" class="px-2.5 py-1 rounded-full text-xs font-semibold flex items-center gap-1.5 {{ $u->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        <span>{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500">
                                {{ $u->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('users.edit', $u) }}" title="Edit Akun" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 hover:bg-teal-500 hover:text-white text-gray-600 dark:text-gray-300 text-xs transition-colors">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>

                                    @if($u->id !== auth()->id() && $u->id !== 1)
                                        <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Akun" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 hover:bg-rose-500 hover:text-white text-gray-600 dark:text-gray-300 text-xs transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Tidak ada data pengguna yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 dark:border-slate-800">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
