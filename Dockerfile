# syntax=docker/dockerfile:1.4

# Stage 1: Builder for PHP dependencies and frontend assets
FROM php:8.2-fpm-alpine AS builder

WORKDIR /var/www/html

# Install system dependencies needed for both composer and npm builds
RUN apk add --no-cache \
    build-base \
    autoconf \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    libpq \
    curl \
    git \
    nodejs \
    npm \
    # Headless Chrome/Chromium for Playwright E2E tests (during build/test stage if needed)
    chromium \
    # For sed compatibility on Alpine
    util-linux \
    # Required by Chromium
    nss \
    freetype \
    harfbuzz \
    dbus \
    gdk-pixbuf \
    libwebp \
    mesa-gl \
    alsa-lib \
    udev \
    # Fonts for Chromium to render pages correctly
    ttf-dejavu \
    openssl # For generating SSL certs

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip exif pcntl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy application code for building
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Scribe for API documentation
RUN composer require knuckleswtf/scribe --dev --no-autoloader --no-scripts && composer dump-autoload

# Generate application key
RUN php artisan key:generate

# Publish vendor assets for Spatie Multitenancy and Sanctum
RUN php artisan vendor:publish --provider="Spatie\Multitenancy\MultitenancyServiceProvider" --tag="multitenancy-config" --force \
    && php artisan vendor:publish --provider="Spatie\Multitenancy\MultitenancyServiceProvider" --tag="multitenancy-migrations" --force \
    && php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --tag="sanctum-config" --force

# Install Scribe assets and generate documentation
RUN php artisan scribe:install --force \
    && php artisan scribe:generate --force

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Stage 2: Production ready image
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install only runtime dependencies
RUN apk add --no-cache \
    nginx \
    curl \
    # Required by Chromium for E2E tests in a running container (if not running tests in CI build stage)
    # nss freetype harfbuzz dbus gdk-pixbuf libwebp mesa-gl alsa-lib udev ttf-dejavu chromium
    # Add openssl for self-signed certs
    openssl

# Install PHP extensions required for runtime
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip exif pcntl gd

# Copy built application code from the builder stage
COPY --from=builder /var/www/html .

# Generate self-signed SSL certificates for Nginx HTTPS
RUN mkdir -p /etc/nginx/certs \
    && openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/nginx/certs/self-signed.key \
    -out /etc/nginx/certs/self-signed.crt \
    -subj "/C=TW/ST=Taipei/L=Taipei/O=LaravelSaaS/CN=localhost"

# Adjust permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expose port 8000 (HTTP) and 8443 (HTTPS) for Nginx
EXPOSE 8000 8443

# Copy Nginx configuration and start script
COPY .docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY .docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Use start.sh as the entrypoint
CMD ["start.sh"]
