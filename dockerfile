
FROM php:8.2-cli

# 1. Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    curl \
    ca-certificates \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        zip \
        mbstring \
        bcmath \
        exif \
        pcntl \
    && rm -rf /var/lib/apt/lists/*

# 2. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Install Node.js 20 for Vite and Tailwind
RUN curl -fsSL https://deb.nodesource.com/setup_20.x -o /tmp/nodesource_setup.sh \
    && bash /tmp/nodesource_setup.sh \
    && apt-get update \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/* /tmp/nodesource_setup.sh

# 4. Set the application directory
WORKDIR /var/www/html

# 5. Copy Laravel project files
COPY . .

# 6. Install production PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# 7. Install frontend dependencies and build Vite assets
RUN npm ci \
    && npm run build \
    && rm -rf node_modules

# 8. Prepare Laravel writable directories
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 9. Render web service port
EXPOSE 8080

# 10. Start Laravel
CMD ["sh", "-c", "php artisan config:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
