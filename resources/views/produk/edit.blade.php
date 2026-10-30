@extends('layouts.app')

@section('title', 'Edit Produk Katalog & Foto')
@section('page-title', 'Edit Produk Katalog')
@section('page-subtitle', 'Perbarui Informasi, Harga, dan Foto Produk ' . $product->nama_produk)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm"
         x-data="{
            imagePreview: '{{ $product->gambar_path ? (str_starts_with($product->gambar_path, 'http') ? $product->gambar_path : asset('storage/' . $product->gambar_path)) : '' }}',
            useUrl: false
         }">
        <!-- Error Alerts -->
        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs">
                <p class="font-bold flex items-center gap-1.5 mb-1">
                    <i class="fas fa-triangle-exclamation"></i> Terjadi kesalahan penyimpanan:
                </p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="source" value="{{ request('source') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Informasi Produk -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Sentra IKM Pembuat</label>
                        <select name="ikm_profile_id" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                            @foreach($ikmProfiles as $ikm)
                                <option value="{{ $ikm->id }}" {{ $product->ikm_profile_id == $ikm->id ? 'selected' : '' }}>
                                    {{ $ikm->nama_usaha }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nama Produk Kerajinan</label>
                        <input type="text" name="nama_produk" value="{{ $product->nama_produk }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Kategori Bahan / Batu</label>
                        <input type="text" name="jenis_batu" value="{{ $product->jenis_batu }}" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Estimasi Harga (Rp)</label>
                        <input type="number" name="harga" value="{{ $product->harga ? (int)$product->harga : '' }}" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Deskripsi Detail Corak & Spesifikasi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">{{ $product->deskripsi }}</textarea>
                    </div>
                </div>

                <!-- Kolom Kanan: Ganti Foto & Preview -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-camera text-teal-500 mr-1"></i> Foto Produk / Gambar Katalog
                        </label>
                        <button type="button" @click="useUrl = !useUrl" class="text-[11px] text-teal-600 dark:text-teal-400 font-semibold hover:underline">
                            <span x-text="useUrl ? 'Ganti ke Upload File' : 'Ganti via Link URL'"></span>
                        </button>
                    </div>

                    <!-- Mode Upload File -->
                    <div x-show="!useUrl" class="border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl p-6 text-center hover:border-teal-500 transition-colors bg-gray-50/50 dark:bg-slate-800/30">
                        <input type="file" name="gambar" id="gambarInputEdit" accept="image/*" class="hidden"
                               @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }">

                        <label for="gambarInputEdit" class="cursor-pointer block">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-image text-xl"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-800 dark:text-white block">Pilih File Foto Baru untuk Mengganti</span>
                            <span class="text-[10px] text-gray-400 mt-1 block">JPG, PNG, WEBP (Maks 20 MB)</span>
                        </label>
                    </div>

                    <!-- Mode Link URL -->
                    <div x-show="useUrl" class="space-y-2">
                        <input type="url" name="gambar_url" placeholder="https://images.unsplash.com/..."
                               @input="imagePreview = $event.target.value"
                               class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        <span class="text-[10px] text-gray-400 block">Masukkan URL foto baru untuk memperbarui gambar katalog.</span>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 block mb-2">Pratinjau Foto Produk Saat Ini / Baru:</span>
                        <div class="h-48 w-full rounded-2xl border border-gray-200 dark:border-slate-700 overflow-hidden bg-slate-900 flex items-center justify-center relative shadow-inner">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Foto Produk" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-6 text-gray-500">
                                    <i class="fas fa-image text-3xl mb-2 text-gray-600"></i>
                                    <p class="text-xs">Belum ada foto</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
                <a href="{{ request('source') === 'catalog' ? route('catalog') : route('produk.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-7 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan & Foto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
