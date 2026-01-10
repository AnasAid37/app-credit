FROM php:8.2-cli

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# PHP deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Node deps
COPY package*.json ./
RUN npm install

# App files
COPY . .

# Frontend build
RUN npm run build || echo "Vite build skipped"

# Permissions
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Laravel cache (آمن)
RUN php artisan config:clear \
    && php artisan config:cache \
    && php artisan view:cache

EXPOSE 8080

CMD php -S 0.0.0.0:8080 -t public
