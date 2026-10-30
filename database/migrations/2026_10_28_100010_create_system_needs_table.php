<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikm_profile_id')->constrained('ikm_profiles')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('aktivitas');
            $table->string('waktu');
            $table->string('alat_sistem');
            $table->text('masalah_kendala');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_needs');
    }
};
