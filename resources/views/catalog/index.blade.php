@extends('layouts.app')

@section('title', 'Katalog Publik & Showcase Produk Marmer')
@section('page-title', 'Katalog Produk Digital')
@section('page-subtitle', 'Galeri Showcase Produk Hasil Kerajinan Tulungagung untuk Buyer')

@section('content')
<div class="space-y-8">

    <!-- Hero Showcase Banner -->
    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 p-8 sm:p-12 border border-teal-500/20 shadow-xl text-white">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <span class="px-3 py-1 rounded-full bg-teal-500/20 text-teal-300 text-xs font-bold uppercase tracking-wider border border-teal-500/30">
                    SEO-Optimized Digital Showcase & Katalog Foto
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">Koleksi Seni Batu Marmer & Alam Tulungagung</h2>
                <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                    Menyajikan detail serat batuan alam asli Besole, marmer hitam, dan andesit sungai untuk mempermudah calon pembeli melihat estetika produk sebelum pemesanan tanpa kirim WhatsApp manual.
                </p>
            </div>

            <!-- Tombol Aksi Katalog -->
            <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('pesanan.public_create') }}" class="px-5 py-3.5 rounded-2xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-black text-xs shadow-lg shadow-teal-500/30 flex items-center justify-center gap-2">
                    <i class="fas fa-wand-magic-sparkles text-slate-950"></i>
                    <span>Punya Desain Sendiri? Pesan Kustom</span>
                </a>
                @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('produk.create', ['source' => 'catalog']) }}" class="px-5 py-3.5 rounded-2xl bg-slate-900/90 hover:bg-slate-800 border border-slate-700 text-white font-bold text-xs flex items-center justify-center gap-2">
                        <i class="fas fa-plus text-teal-400"></i>
                        <span>Tambah Produk (Admin)</span>
                    </a>
                @endif
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Filter & Action Bar -->
    <div class="glass p-5 rounded-3xl border border-gray-200/70 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('catalog') }}" class="flex flex-wrap items-center gap-3">
            <select name="ikm" class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200">
                <option value="">Semua Sentra IKM</option>
                @foreach($ikmProfiles as $ikm)
                    <option value="{{ $ikm->id }}" {{ request('ikm') == $ikm->id ? 'selected' : '' }}>{{ $ikm->nama_usaha }}</option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari wastafel, stepping..." class="px-4 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200">

            <button type="submit" class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold shadow-sm transition-all">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            @if(request()->anyFilled(['ikm', 'search']))
                <a href="{{ route('catalog') }}" class="text-xs text-gray-400 hover:text-gray-600">Reset</a>
            @endif
        </form>

        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-500 font-semibold">{{ $products->total() }} Produk Tersedia</span>
            <a href="{{ route('produk.create', ['source' => 'catalog']) }}" class="px-3 py-1.5 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 text-xs font-bold border border-teal-500/20 hover:bg-teal-600 hover:text-white transition-all flex items-center gap-1">
                <i class="fas fa-plus"></i> Tambah Baru
            </a>
        </div>
    </div>

    <!-- Products Grid with Edit & Image Management -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="glass rounded-3xl border border-gray-200/70 dark:border-slate-800 overflow-hidden shadow-sm card-hover flex flex-col group relative">
                <!-- Image Container -->
                <div class="h-56 w-full bg-slate-900 relative overflow-hidden flex items-center justify-center">
                    @if($product->gambar_path)
                        @php
                            $imgSrc = str_starts_with($product->gambar_path, 'http') ? $product->gambar_path : asset('storage/' . $product->gambar_path);
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <!-- Graphic Aesthetic Fallback Placeholder -->
                        <div class="text-center p-6 bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 w-full h-full flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-2xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 text-2xl mb-2">
                                <i class="fas fa-gem"></i>
                            </div>
                            <span class="text-xs font-bold text-white tracking-wide">{{ $product->nama_produk }}</span>
                            <span class="text-[10px] text-teal-400/80 font-mono mt-0.5">{{ $product->jenis_batu }}</span>
                        </div>
                    @endif

                    <!-- Sentra IKM Badge -->
                    <div class="absolute top-3 left-3 z-10">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-slate-950/80 text-teal-400 backdrop-blur-md border border-slate-700/80 shadow-md">
                            {{ $product->ikmProfile->nama_usaha }}
                        </span>
                    </div>

                    <!-- Tombol Edit Cepat Foto & Data di Kartu -->
                    <div class="absolute top-3 right-3 z-10 flex items-center gap-1.5 opacity-90 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('produk.edit', [$product, 'source' => 'catalog']) }}"
                           title="Edit produk & ganti foto"
                           class="px-2.5 py-1.5 rounded-xl bg-slate-950/80 hover:bg-teal-600 text-white text-xs font-semibold backdrop-blur-md border border-slate-700 transition-all shadow-md flex items-center gap-1">
                            <i class="fas fa-pen-to-square text-[10px]"></i>
                            <span class="text-[10px]">Edit Foto</span>
                        </a>
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[11px] font-bold text-teal-600 dark:text-teal-400 uppercase tracking-wider">{{ $product->jenis_batu }}</span>
                            @if(!$product->gambar_path)
                                <a href="{{ route('produk.edit', [$product, 'source' => 'catalog']) }}" class="text-[10px] text-amber-500 hover:underline font-semibold">
                                    <i class="fas fa-camera"></i> Tambah Foto
                                </a>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-teal-500 transition-colors">
                            {{ $product->nama_produk }}
                        </h3>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">
                            {{ $product->deskripsi ?? 'Hasil olahan pengrajin marmer Tulungagung dengan pengerjaan detail dan polesan berkelas.' }}
                        </p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-gray-400 block font-medium">Harga Produk Ready</span>
                            <span class="text-base font-extrabold text-emerald-600 dark:text-emerald-400">
                                {{ $product->harga ? 'Rp ' . number_format($product->harga, 0, ',', '.') : 'Rp 450.000' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <a href="{{ route('produk.edit', [$product, 'source' => 'catalog']) }}" class="p-2 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 text-xs" title="Edit Katalog">
                                    <i class="fas fa-pencil"></i>
                                </a>
                            @endif
                            <!-- Tombol Beli Sekarang Langsung ke Checkout -->
                            <a href="{{ route('catalog.checkout', $product) }}" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold shadow-md shadow-teal-500/20 transition-all flex items-center gap-1.5 hover:scale-105 active:scale-95">
                                <i class="fas fa-bolt text-amber-300"></i> Beli Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-16 text-center text-gray-400 glass rounded-3xl border border-gray-200 dark:border-slate-800">
                <i class="fas fa-boxes-stacked text-4xl text-gray-500 mb-3"></i>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Belum Ada Produk di Katalog</h3>
                <p class="text-xs text-gray-500 mt-1 mb-4">Tambahkan produk pertama lengkap dengan foto untuk memikat calon pembeli.</p>
                <a href="{{ route('produk.create', ['source' => 'catalog']) }}" class="px-5 py-2.5 rounded-xl bg-teal-600 text-white font-bold text-xs inline-flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Produk Sekarang
                </a>
            </div>
        @endforelse
    </div>

    <div>
        {{ $products->links() }}
    </div>

</div>
@endsection
