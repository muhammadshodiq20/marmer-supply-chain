<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikm_profile_id')->constrained('ikm_profiles')->cascadeOnDelete();
            $table->string('kode_order')->unique();
            $table->string('nama_pelanggan');
            $table->string('telepon_pelanggan')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->string('jenis_produk');
            $table->integer('jumlah');
            $table->text('spesifikasi')->nullable();
            $table->string('status')->default('order_received');
            $table->date('tanggal_order');
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
