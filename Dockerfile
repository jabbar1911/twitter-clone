# Stage 1: Build Frontend Assets
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Production PHP + Nginx Environment
FROM php:8.3-fpm-alpine

# Install system packages & dependencies
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
    oniguruma-dev

# Install PHP extensions for Laravel (PostgreSQL, SQLite, PDO, Zip, BCMath, Opcache)
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

# Copy built frontend assets from Stage 1
COPY --from=node_builder /app/public/build ./public/build

# Install production PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy Nginx config & Entrypoint script
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set directory permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
