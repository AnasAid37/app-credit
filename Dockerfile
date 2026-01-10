FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev zip unzip nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring gd zip \
    && apt-get clean

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY package*.json ./
RUN npm ci || npm install

COPY . .

RUN composer dump-autoload --optimize
RUN npm run build || echo "Build skipped"

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Create log file
RUN touch storage/logs/laravel.log \
    && chmod 666 storage/logs/laravel.log

EXPOSE 8080

CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan migrate --force && \
    php artisan storage:link && \
    echo "Starting Laravel server..." && \
    php artisan serve --host=0.0.0.0 --port=8080 --verbose