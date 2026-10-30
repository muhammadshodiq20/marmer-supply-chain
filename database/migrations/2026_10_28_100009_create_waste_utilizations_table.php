<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_utilizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_record_id')->nullable()->constrained('waste_records')->nullOnDelete();
            $table->foreignId('ikm_profile_id')->constrained('ikm_profiles')->cascadeOnDelete();
            $table->string('jenis_limbah');
            $table->string('volume_estimasi')->nullable();
            $table->string('pemanfaatan');
            $table->string('status')->default('tersedia');
            $table->decimal('harga_jual', 12, 2)->nullable();
            $table->string('pembeli')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_utilizations');
    }
};
