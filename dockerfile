# ============================================
# Dockerfile optimisé pour Render.com
# Laravel 11 avec PHP 8.2
# ============================================

FROM php:8.2-fpm-alpine

# Installer les dépendances système
RUN apk add --no-cache \
    nginx \
    supervisor \
    nodejs \
    npm \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    mysql-client \
    bash

# Extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configuration PHP Production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/custom.ini

# Créer utilisateur
RUN addgroup -g 1000 laravel && \
    adduser -D -u 1000 -G laravel laravel

# Nginx config
RUN rm -rf /etc/nginx/http.d/default.conf
COPY <<EOF /etc/nginx/http.d/default.conf
server {
    listen 8080;
    server_name _;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Supervisor config
RUN mkdir -p /var/log/supervisor
COPY <<EOF /etc/supervisor/conf.d/supervisord.conf
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log

[program:php-fpm]
command=php-fpm -F
autorestart=true
stdout_logfile=/dev/stdout
stderr_logfile=/dev/stderr
stdout_logfile_maxbytes=0
stderr_logfile_maxbytes=0

[program:nginx]
command=nginx -g 'daemon off;'
autorestart=true
stdout_logfile=/dev/stdout
stderr_logfile=/dev/stderr
stdout_logfile_maxbytes=0
stderr_logfile_maxbytes=0
EOF

WORKDIR /var/www/html

# Copier application
COPY --chown=laravel:laravel . .

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Installer et compiler assets
RUN npm ci && npm run build

# Permissions
RUN chown -R laravel:laravel /var/www/html && \
    chmod -R 755 storage bootstrap/cache

# Script de démarrage
COPY <<'EOF' /start.sh
#!/bin/sh
set -e

echo "🚀 Starting Pneumatique Aqabli..."

# Attendre MySQL
if [ -n "$DB_HOST" ]; then
    echo "⏳ Waiting for MySQL..."
    until nc -z -v -w30 $DB_HOST 3306 2>/dev/null; do
        sleep 2
    done
    echo "✅ MySQL ready!"
fi

# Migrations
php artisan migrate --force

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 755 storage bootstrap/cache

echo "✅ Application ready!"

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
EOF

RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]