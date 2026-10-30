<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('process_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikm_profile_id')->constrained('ikm_profiles')->cascadeOnDelete();
            $table->integer('nomor_urut');
            $table->string('tahap_proses');
            $table->text('aktor_terlibat');
            $table->text('informasi_seharusnya');
            $table->text('kondisi_saat_ini');
            $table->text('gap_teridentifikasi');
            $table->string('jenis_waste');
            $table->text('dampak');
            $table->string('jenis_produk_konteks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('process_flows');
    }
};
