FROM php:8.1-fpm

WORKDIR /var/www

RUN \
    apt-get update -y \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql iconv \
    && rm -Rf /var/www/html

COPY usr/local/etc/php/conf.d/settings.ini  /usr/local/etc/php/conf.d/40-php.ini
