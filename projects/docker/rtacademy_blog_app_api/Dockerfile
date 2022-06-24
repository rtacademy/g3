FROM php:8.1-fpm

ENV APP_ENV=prod
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV TIMEZONE="Europe/Kiev"

RUN \
    set -eux; \
    apt update -y; \
    apt upgrade -y; \
    ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone ; \
    curl -sS 'https://getcomposer.org/installer' | php -- --install-dir=/usr/local/bin --filename=composer ;\
    curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash; \
    apt install -y  \
        symfony-cli  \
        libicu-dev  \
        unzip \
        gnupg \
        g++ \
        procps \
        openssl \
        zlib1g-dev \
        libzip-dev \
        libfreetype6-dev \
        libpng-dev \
        libjpeg-dev \
        libicu-dev  \
        libonig-dev \
        libxslt1-dev \
        acl; \
    docker-php-ext-install -j$(nproc) intl opcache pdo_mysql iconv zip && \
    docker-php-ext-enable iconv intl opcache pdo_mysql && \
    rm -Rf /var/www/html

COPY ./docker/rtacademy_blog_app_api/etc/php/conf.d/php.ini     /usr/local/etc/php/conf.d/
COPY ./blog_api                                                 /var/www/

WORKDIR /var/www/blog_api
