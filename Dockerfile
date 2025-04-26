FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    freetype-dev \
    jpeg-dev \
    libpng-dev \
    postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql pgsql

WORKDIR /var/www/html
