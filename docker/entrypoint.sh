#!/bin/sh
set -e

echo "==> Setting up storage and caches..."
php artisan storage:link || true

# Run database migrations and seeding
echo "==> Running database migrations..."
php artisan migrate --force --seed || true

# Cache configurations for maximum production performance
echo "==> Optimizing Laravel..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "==> Starting PHP-FPM and Nginx..."
php-fpm -D
nginx -g "daemon off;"
