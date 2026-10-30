@extends('layouts.app')

@section('title', 'Tambah Produk & Foto Katalog Baru')
@section('page-title', 'Tambah Produk Katalog')
@section('page-subtitle', 'Unggah Produk Kerajinan Marmer / Batu Alam Lengkap dengan Foto')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass p-8 rounded-3xl border border-gray-200/70 dark:border-slate-800 shadow-sm" x-data="{ imagePreview: null, useUrl: false }">
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

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="source" value="{{ request('source') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Informasi Produk -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Sentra IKM Pembuat</label>
                        <select name="ikm_profile_id" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                            @foreach($ikmProfiles as $ikm)
                                <option value="{{ $ikm->id }}">{{ $ikm->nama_usaha }} ({{ $ikm->fokus_produksi }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nama Produk Kerajinan</label>
                        <input type="text" name="nama_produk" placeholder="Contoh: Wastafel Mangkok Marmer Putih Besole" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Kategori Bahan / Batu</label>
                        <input type="text" name="jenis_batu" placeholder="Contoh: Marmer Putih Besole / Batu Kali Sungai / Onix" required class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Harga Estimasi (Rp)</label>
                        <input type="number" name="harga" placeholder="Contoh: 450000" class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Deskripsi Detail Corak & Spesifikasi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Jelaskan karakteristik corak serat alami marmer, ukuran diameter, tinggi, kehalusan finishing Hi-Glossy..." class="w-full px-4 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500"></textarea>
                    </div>
                </div>

                <!-- Kolom Kanan: Upload & Preview Foto Produk -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-camera text-teal-500 mr-1"></i> Foto Produk / Motif Marmer
                        </label>
                        <button type="button" @click="useUrl = !useUrl" class="text-[11px] text-teal-600 dark:text-teal-400 font-semibold hover:underline">
                            <span x-text="useUrl ? 'Ganti ke Upload File' : 'Pakai URL Link Gambar'"></span>
                        </button>
                    </div>

                    <!-- Mode Upload File File -->
                    <div x-show="!useUrl" class="border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl p-6 text-center hover:border-teal-500 transition-colors bg-gray-50/50 dark:bg-slate-800/30">
                        <input type="file" name="gambar" id="gambarInput" accept="image/*" class="hidden"
                               @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }">

                        <label for="gambarInput" class="cursor-pointer block">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-cloud-arrow-up text-xl"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-800 dark:text-white block">Klik untuk Unggah Foto dari Perangkat</span>
                            <span class="text-[10px] text-gray-400 mt-1 block">Format: JPG, PNG, WEBP (Maks 20 MB)</span>
                        </label>
                    </div>

                    <!-- Mode Input URL Online -->
                    <div x-show="useUrl" class="space-y-2">
                        <input type="url" name="gambar_url" placeholder="https://images.unsplash.com/photo-..."
                               @input="imagePreview = $event.target.value"
                               class="w-full px-4 py-2.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-teal-500">
                        <span class="text-[10px] text-gray-400 block">Tempelkan tautan gambar resolusi tinggi untuk katalog.</span>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 block mb-2">Pratinjau (Preview) Tampilan Foto:</span>
                        <div class="h-48 w-full rounded-2xl border border-gray-200 dark:border-slate-700 overflow-hidden bg-slate-900 flex items-center justify-center relative shadow-inner">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Preview Foto Produk" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-6 text-gray-500">
                                    <i class="fas fa-image text-3xl mb-2 text-gray-600"></i>
                                    <p class="text-xs">Belum ada foto yang dipilih</p>
                                </div>
                            </template>
                            <template x-if="imagePreview">
                                <button type="button" @click="imagePreview = null; document.getElementById('gambarInput').value = ''"
                                        class="absolute top-2 right-2 px-2.5 py-1 rounded-lg bg-rose-600 text-white text-[10px] font-bold shadow-md hover:bg-rose-700">
                                    <i class="fas fa-times mr-1"></i> Hapus
                                </button>
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
                    <i class="fas fa-save"></i> Simpan ke Katalog
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
