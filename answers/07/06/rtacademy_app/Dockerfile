FROM php:8.1-fpm

WORKDIR /var/www

RUN \
    apt-get update -y \
    && apt-get upgrade -y \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql pdo_pgsql iconv \
    && rm -Rf /var/www/html

COPY etc/php/conf.d/40-php.ini  /usr/local/etc/php/conf.d/

