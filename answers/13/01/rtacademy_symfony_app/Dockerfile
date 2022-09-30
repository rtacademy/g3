FROM php:8.1-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV TIMEZONE="Europe/Kiev"

WORKDIR /var/www

RUN \
    apt-get update -y \
    && apt-get upgrade -y \
    && ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && apt-get install -y \
        apt-transport-https \
        libfreetype6-dev  \
        libjpeg62-turbo-dev  \
        libjpeg-dev \
        libpng-dev  \
        libpq-dev  \
        curl libcurl4-openssl-dev  \
        zlib1g-dev  \
        g++  \
        git  \
        libicu-dev \
        libzip-dev  \
        zip  \
        unzip \
        gnupg \
        procps \
        openssl \
        libfreetype6-dev \
        libonig-dev \
        libxslt1-dev \
        acl \
    && apt-get install -y libmagickwand-dev --no-install-recommends \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
    && apt-get install -y symfony-cli \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure curl  \
    && docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc) curl fileinfo gd iconv intl opcache pdo_mysql zip \
    && printf "\n" | pecl install imagick \
    && docker-php-ext-enable imagick \
    && printf "\n" | pecl install apcu \
    && docker-php-ext-enable apcu iconv intl opcache pdo_mysql \
    && apt clean \
    && rm -Rf /var/www/html; rm -f /var/log/* /var/log/*/* ; rm -rf /var/lib/apt/* /tmp/* /var/tmp/* /var/cache/* /var/lib/dpkg/*

COPY etc/php/conf.d/40-php.ini  /usr/local/etc/php/conf.d/
