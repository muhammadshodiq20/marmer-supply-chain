<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'diverifikasi_oleh')) {
                $table->string('diverifikasi_oleh')->nullable()->after('tanggal_pembayaran');
            }
            if (!Schema::hasColumn('orders', 'tanggal_verifikasi')) {
                $table->timestamp('tanggal_verifikasi')->nullable()->after('diverifikasi_oleh');
            }
            if (!Schema::hasColumn('orders', 'nominal_verifikasi')) {
                $table->decimal('nominal_verifikasi', 15, 2)->nullable()->after('tanggal_verifikasi');
            }
            if (!Schema::hasColumn('orders', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('nominal_verifikasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'diverifikasi_oleh',
                'tanggal_verifikasi',
                'nominal_verifikasi',
                'catatan_verifikasi'
            ]);
        });
    }
};
