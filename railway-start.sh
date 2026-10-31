#!/bin/bash
set -e

echo "===> [Railway Startup] Memeriksa konfigurasi database..."

if [ -z "$DATABASE_URL" ] && [ -n "$MYSQL_URL" ]; then
    echo "===> Menggunakan MYSQL_URL sebagai DATABASE_URL..."
    export DATABASE_URL="$MYSQL_URL"
fi

if [ -n "$MYSQLHOST" ]; then
    echo "===> Terdeteksi host MySQL Railway: $MYSQLHOST (Port: ${MYSQLPORT:-3306})"
fi

# Pastikan APP_KEY & Konfigurasi URL HTTPS tersedia
if [ -z "$APP_KEY" ]; then
    echo "===> Generate temporary APP_KEY..."
    export APP_KEY="base64:XG8o396F/eH1q8v/t+W1lX8Q0zP5J2sL1t3g4i6u7e8="
fi

export APP_ENV="production"
if [ -n "$RAILWAY_PUBLIC_DOMAIN" ]; then
    export APP_URL="https://$RAILWAY_PUBLIC_DOMAIN"
elif [ -z "$APP_URL" ]; then
    export APP_URL="https://marmer-supply-chain-production.up.railway.app"
fi
export ASSET_URL="$APP_URL"

echo "===> Membersihkan cache config & view..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "===> Menunggu koneksi MySQL aktif (membangunkan instance jika sedang sleeping)..."
MAX_TRIES=10
COUNT=0
DB_READY=false

while [ $COUNT -lt $MAX_TRIES ]; do
    if php artisan db:monitor >/dev/null 2>&1 || php -r '
        $url = getenv("DATABASE_URL") ?: getenv("MYSQL_URL");
        $host = getenv("DB_HOST") ?: getenv("MYSQLHOST") ?: "127.0.0.1";
        $port = getenv("DB_PORT") ?: getenv("MYSQLPORT") ?: "3306";
        $user = getenv("DB_USERNAME") ?: getenv("MYSQLUSER") ?: "root";
        $pass = getenv("DB_PASSWORD") ?: getenv("MYSQLPASSWORD") ?: "";
        $db   = getenv("DB_DATABASE") ?: getenv("MYSQLDATABASE") ?: "railway";

        if ($url) {
            $parts = parse_url($url);
            $host = $parts["host"] ?? $host;
            $port = $parts["port"] ?? $port;
            $user = $parts["user"] ?? $user;
            $pass = $parts["pass"] ?? $pass;
            $db   = ltrim($parts["path"] ?? $db, "/");
        }
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass, [PDO::ATTR_TIMEOUT => 3]);
        exit(0);
    ' >/dev/null 2>&1; then
        echo "===> [OK] Database MySQL terhubung dan aktif!"
        DB_READY=true
        break
    else
        COUNT=$((COUNT+1))
        echo "===> [Percobaan $COUNT/$MAX_TRIES] Database masih booting/sleeping, menunggu 4 detik..."
        sleep 4
    fi
done

if [ "$DB_READY" = true ]; then
    echo "===> Menjalankan Migrasi Database..."
    php artisan migrate --force || echo "Migrasi menemui error, melanjutkan..."

    echo "===> Menjalankan Seeder Data Awal (User, Produk, dll)..."
    php artisan db:seed --force || echo "Seeder sudah terisi atau dilewati."
else
    echo "===> [PERINGATAN] Database belum merespons setelah 40 detik. Web server tetap akan dijalankan agar tidak crash."
fi

echo "===> Membuat Storage Link..."
php artisan storage:link || true

echo "===> Menjalankan Web Server Laravel pada Port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
