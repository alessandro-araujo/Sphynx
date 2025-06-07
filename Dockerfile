FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    freetype-dev \
    jpeg-dev \
    libpng-dev \
    postgresql-dev \
    mysql-client \
    mariadb-connector-c-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql pgsql pdo_mysql

WORKDIR /var/www/html

