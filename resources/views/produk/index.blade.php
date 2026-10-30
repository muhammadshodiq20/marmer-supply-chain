@extends('layouts.app')

@section('title', 'Manajemen Master Produk')
@section('page-title', 'Koleksi & Master Produk')
@section('page-subtitle', 'Kelola Master Data, Deskripsi, Harga, & Foto Produk IKM Marmer')

@section('content')
<div class="space-y-6">

    <!-- Action & Filter Bar -->
    <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('produk.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 w-60">
            
            <select name="ikm" class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua IKM</option>
                @foreach($ikmProfiles as $ikm)
                    <option value="{{ $ikm->id }}" {{ request('ikm') == $ikm->id ? 'selected' : '' }}>{{ $ikm->nama_usaha }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold shadow-sm">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'ikm']))
                <a href="{{ route('produk.index') }}" class="text-xs text-gray-400">Reset</a>
            @endif
        </form>

        <div class="flex items-center gap-3 self-start md:self-auto">
            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-xs font-semibold hover:bg-gray-100 dark:hover:bg-slate-800 transition-all">
                <i class="fas fa-eye text-teal-500"></i> Lihat Katalog Publik
            </a>
            <a href="{{ route('produk.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 transition-all">
                <i class="fas fa-plus"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 overflow-hidden shadow-sm flex flex-col justify-between group">
                <div>
                    <!-- Thumbnail Container -->
                    <div class="h-44 w-full bg-slate-900 relative overflow-hidden flex items-center justify-center">
                        @if($product->gambar_path)
                            @php
                                $imgSrc = str_starts_with($product->gambar_path, 'http') ? $product->gambar_path : asset('storage/' . $product->gambar_path);
                            @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-4">
                                <i class="fas fa-image text-3xl text-gray-600 mb-2"></i>
                                <p class="text-[11px] text-gray-400">Belum ada foto produk</p>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="text-xs px-2.5 py-1 rounded-md bg-slate-950/80 text-teal-400 font-semibold backdrop-blur-md border border-slate-700">
                                {{ $product->ikmProfile->nama_usaha }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[11px] text-teal-600 dark:text-teal-400 font-bold uppercase tracking-wider">{{ $product->jenis_batu }}</span>
                            <span class="text-xs font-extrabold text-gray-900 dark:text-white">
                                {{ $product->harga ? 'Rp ' . number_format($product->harga, 0, ',', '.') : 'Harga Hubungi Perajin' }}
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $product->nama_produk }}</h3>
                        <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">{{ $product->deskripsi }}</p>
                    </div>
                </div>

                <div class="p-6 pt-0 border-t border-gray-100 dark:border-slate-800/60 mt-4 flex items-center justify-between">
                    <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                        <i class="fas fa-check-circle"></i> Tayang di Katalog
                    </span>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('produk.edit', $product) }}" class="px-3 py-1.5 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 hover:bg-teal-600 hover:text-white text-xs font-bold transition-all flex items-center gap-1">
                            <i class="fas fa-pen-to-square"></i> Edit & Foto
                        </a>

                        <form action="{{ route('produk.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari database?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 text-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-16 text-center text-gray-400 glass rounded-3xl border border-gray-200 dark:border-slate-800">
                <i class="fas fa-cubes text-4xl text-gray-500 mb-3"></i>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Belum Ada Produk</h3>
                <p class="text-xs text-gray-500 mt-1 mb-4">Tambahkan master produk kerajinan marmer untuk ditampilkan di sistem dan katalog.</p>
                <a href="{{ route('produk.create') }}" class="px-5 py-2.5 rounded-xl bg-teal-600 text-white font-bold text-xs inline-flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
            </div>
        @endforelse
    </div>

    <div>{{ $products->links() }}</div>

</div>
@endsection
