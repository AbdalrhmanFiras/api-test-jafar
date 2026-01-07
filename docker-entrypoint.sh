#!/bin/bash
set -e

echo "Fixing Laravel permissions..."

# صلاحيات storage و cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# إنشاء أي مجلد مفقود
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public/profiles
mkdir -p /var/www/html/bootstrap/cache

# إنشاء الرابط للملفات
php artisan storage:link || true

# مسح cache و config و route
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Cache config for production
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# تشغيل Apache
exec apache2-foreground
