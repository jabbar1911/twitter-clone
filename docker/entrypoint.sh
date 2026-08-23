#!/bin/sh
set -e

# If APP_KEY is missing or empty, generate one
if [ -z "$APP_KEY" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --force || true
fi

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

# Ensure all storage & database directories have correct permissions for www-data
echo "==> Setting file permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

echo "==> Starting PHP-FPM and Nginx..."
php-fpm -D
nginx -g "daemon off;"
