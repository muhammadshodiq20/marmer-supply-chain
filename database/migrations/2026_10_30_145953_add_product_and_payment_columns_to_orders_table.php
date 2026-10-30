<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'tipe_pesanan')) {
                $table->string('tipe_pesanan')->default('kustom')->after('user_id'); // 'katalog' atau 'kustom'
            }
            if (!Schema::hasColumn('orders', 'product_id')) {
                $table->foreignId('product_id')->nullable()->after('tipe_pesanan')->constrained('products')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'harga_satuan')) {
                $table->decimal('harga_satuan', 15, 2)->default(0)->after('jumlah');
            }
            if (!Schema::hasColumn('orders', 'biaya_pengiriman')) {
                $table->decimal('biaya_pengiriman', 15, 2)->default(0)->after('harga_satuan');
            }
            if (!Schema::hasColumn('orders', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('status_pembayaran'); // transfer_bca, transfer_mandiri, cod
            }
            if (!Schema::hasColumn('orders', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('orders', 'tanggal_pembayaran')) {
                $table->timestamp('tanggal_pembayaran')->nullable()->after('bukti_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn([
                'tipe_pesanan',
                'product_id',
                'harga_satuan',
                'biaya_pengiriman',
                'metode_pembayaran',
                'bukti_pembayaran',
                'tanggal_pembayaran'
            ]);
        });
    }
};
