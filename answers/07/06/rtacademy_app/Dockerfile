FROM php:8.1-fpm

WORKDIR /var/www

RUN \
    apt-get update -y \
    && apt-get upgrade -y \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libpq-dev curl libcurl4-openssl-dev libicu-dev \
    && apt-get install -y libmagickwand-dev --no-install-recommends \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure curl \
    && docker-php-ext-install -j$(nproc) curl fileinfo gd iconv intl pdo_mysql pdo_pgsql \
    && printf "\n" | pecl install imagick \
    && docker-php-ext-enable imagick \
    && apt clean \
    && rm -Rf /var/www/html; rm -f /var/log/* /var/log/*/* ; rm -rf /var/lib/apt/* /tmp/* /var/tmp/* /var/cache/* /var/lib/dpkg/*

COPY etc/php/conf.d/40-php.ini  /usr/local/etc/php/conf.d/
