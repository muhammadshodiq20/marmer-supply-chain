<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikm_profile_id')->constrained('ikm_profiles')->cascadeOnDelete();
            $table->integer('tahun');
            $table->string('bulan');
            $table->string('jenis_batu');
            $table->string('jumlah_pembelian');
            $table->string('dimensi')->nullable();
            $table->string('warna')->nullable();
            $table->string('status')->default('tersedia');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
