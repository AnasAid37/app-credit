FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring gd zip exif pcntl bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Install Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

WORKDIR /app

# Copy dependency files
COPY composer.json composer.lock ./
COPY package*.json ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Install Node dependencies
RUN npm ci --production=false || npm install

# Copy application files
COPY . .

# Build assets
RUN npm run build

# Generate optimized autoload
RUN composer dump-autoload --optimize

# Create required directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage \
    && chmod -R 777 bootstrap/cache

# Create log file
RUN touch storage/logs/laravel.log \
    && chmod 666 storage/logs/laravel.log

# Expose port
EXPOSE 8080

# Start script with error handling
CMD set -e && \
    echo "🚀 Starting application..." && \
    echo "📋 Checking environment..." && \
    php -v && \
    echo "🔧 Clearing caches..." && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan route:clear && \
    echo "🗄️ Running migrations..." && \
    php artisan migrate --force --no-interaction || echo "⚠️ Migration failed, continuing..." && \
    echo "🔗 Creating storage link..." && \
    (php artisan storage:link || echo "Storage link already exists") && \
    echo "✅ Starting server on 0.0.0.0:8080..." && \
    php artisan serve --host=0.0.0.0 --port=8080 --tries=3