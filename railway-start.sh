#!/bin/bash

echo "===> Memeriksa variabel lingkungan database..."
# Jika DATABASE_URL ada di Railway (diberikan otomatis oleh Railway MySQL)
if [ -n "$DATABASE_URL" ]; then
    echo "DATABASE_URL ditemukan: Menggunakan koneksi default Railway MySQL."
fi

# Tunggu database siap jika sedang sleeping/booting
echo "===> Menunggu koneksi database siap..."
sleep 5

echo "===> Membersihkan cache lama..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "===> Menjalankan Migrasi Database..."
php artisan migrate --force || {
    echo "PERINGATAN: Migrasi awal gagal (kemungkinan DB sedang booting). Mencoba kembali dalam 5 detik..."
    sleep 5
    php artisan migrate --force || echo "Migrasi dilewati agar server tetap menyala."
}

echo "===> Menjalankan Seeder Data Awal..."
php artisan db:seed --force || echo "Seeder sudah terisi atau dilewati."

echo "===> Membuat Storage Link..."
php artisan storage:link || true

echo "===> Menjalankan Web Server Laravel pada Port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
