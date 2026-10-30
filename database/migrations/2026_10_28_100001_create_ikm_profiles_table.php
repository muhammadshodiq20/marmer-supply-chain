<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ikm_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->string('nama_usaha');
            $table->string('fokus_produksi');
            $table->string('lokasi');
            $table->string('kekuatan_produksi');
            $table->text('deskripsi')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ikm_profiles');
    }
};
