#!/bin/bash
set -e

echo "===> Menyiapkan Cache Konfigurasi & View..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "===> Menjalankan Migrasi Database..."
php artisan migrate --force

echo "===> Menjalankan Database Seeder (Jika Belum Ada Data)..."
php artisan db:seed --force || echo "Seeder skipped or already seeded"

echo "===> Membuat Storage Link..."
php artisan storage:link || true

echo "===> Menjalankan Server Laravel di Port $PORT..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
