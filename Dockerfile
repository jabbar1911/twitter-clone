# Production PHP 8.4 + Nginx + Node Environment for Laravel 12
FROM php:8.4-fpm-alpine

# Install system packages, Nginx, Node.js & NPM
RUN apk add --no-cache \
    nginx \
    sqlite \
    sqlite-dev \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    oniguruma-dev

# Install PHP extensions for Laravel (PostgreSQL, SQLite, MySQL, PDO, Zip, BCMath, Opcache)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    pdo_pgsql \
    pgsql \
    zip \
    bcmath \
    opcache \
    mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application files
COPY . .

# Ensure .env and database file exist during build
RUN if [ ! -f .env ] && [ -f .env.example ]; then cp .env.example .env; fi && \
    mkdir -p database && touch database/database.sqlite

# Install PHP dependencies first (so PHP and artisan are ready for Vite)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install NPM dependencies and build frontend assets with Vite
RUN npm ci && npm run build

# Copy Nginx config & Entrypoint script
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set directory permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
