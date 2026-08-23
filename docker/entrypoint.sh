#!/bin/sh
set -e

# 1. Ensure .env file exists
if [ ! -f /var/www/.env ]; then
    echo "==> Creating .env file from .env.example..."
    if [ -f /var/www/.env.example ]; then
        cp /var/www/.env.example /var/www/.env
    else
        touch /var/www/.env
    fi
fi

# 2. Ensure SQLite database file exists
mkdir -p /var/www/database
touch /var/www/database/database.sqlite

# 3. Ensure APP_KEY exists in .env or environment
if [ -z "$APP_KEY" ]; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# 4. Link public storage
echo "==> Linking storage..."
php artisan storage:link || true

# 5. Run database migrations and seeding
echo "==> Running database migrations and seeders..."
php artisan migrate --force --seed || true

# 6. Cache configurations for maximum production performance
echo "==> Optimizing Laravel..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# 7. Ensure all storage & database directories have correct permissions for www-data
echo "==> Setting file permissions for web worker..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database /var/www/.env
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database /var/www/.env

echo "==> Starting PHP-FPM and Nginx..."
php-fpm -D
nginx -g "daemon off;"
