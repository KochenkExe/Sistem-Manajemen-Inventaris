#!/bin/sh

# 1. Sesuaikan port listen Nginx secara dinamis dengan port yang diberikan Railway (dan pertahankan port 8000)
if [ -n "${PORT}" ] && [ "${PORT}" != "8000" ]; then
    echo "Mengatur Nginx untuk mendengarkan di port 8000 dan ${PORT}..."
    sed -i "s/listen 8000;/listen 8000; listen ${PORT};/g" /etc/nginx/http.d/default.conf
else
    echo "Mengatur Nginx untuk mendengarkan di port 8000..."
fi

# 2. Jalankan migrasi database
echo "Menjalankan migrasi database..."
php artisan migrate --force

# 3. Jalankan seeding secara kondisional jika tabel Role masih kosong
echo "Mengecek isi database..."
ROLE_COUNT=$(php artisan tinker --execute="echo App\Models\Role::count();")
if [ "$ROLE_COUNT" -eq "0" ]; then
    echo "Database kosong. Menjalankan DB Seeder..."
    php artisan db:seed --force
else
    echo "Database sudah terisi. Melewati DB Seeder."
fi

# 4. Buat symlink storage
echo "Membuat storage link..."
php artisan storage:link --force || true

# 5. Optimasi Laravel Caching
echo "Melakukan caching konfigurasi dan rute..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Jalankan supervisord untuk menghidupkan Nginx & PHP-FPM
echo "Memulai Nginx dan PHP-FPM lewat Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
