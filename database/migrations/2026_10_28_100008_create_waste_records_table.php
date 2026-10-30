<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikm_profile_id')->constrained('ikm_profiles')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('aktivitas_diamati');
            $table->string('waktu_mulai');
            $table->string('waktu_selesai');
            $table->string('jenis_waste');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_records');
    }
};
