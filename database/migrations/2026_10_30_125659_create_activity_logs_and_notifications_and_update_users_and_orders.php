<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah field alamat, is_active di users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('telepon');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('alamat');
            }
        });

        // 2. Tambah relasi user_id & total_harga & status_pembayaran di orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('ikm_profile_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'total_harga')) {
                $table->decimal('total_harga', 15, 2)->default(0)->after('jumlah');
            }
            if (!Schema::hasColumn('orders', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('menunggu_pembayaran')->after('status');
            }
        });

        // 3. Buat tabel notifications
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->string('judul');
                $table->text('pesan');
                $table->string('tipe')->default('info'); // info, order, stock, waste, alert
                $table->string('link')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }

        // 4. Buat tabel activity_logs
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('nama_pengguna')->nullable();
                $table->string('peran')->nullable();
                $table->string('aktivitas');
                $table->text('deskripsi')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notifications');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'total_harga', 'status_pembayaran']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'is_active']);
        });
    }
};
