FROM php:8.1-fpm

WORKDIR /var/www

RUN \
    set -eux; \
    apt update -y; \
    apt upgrade -y; \
    cd /usr/local/bin && curl -1sLf 'https://getcomposer.org/installer' | php && ln -s ./composer.phar ./composer ;\
    curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash; \
    apt install -y symfony-cli libicu-dev && \
    docker-php-ext-install -j$(nproc) intl opcache pdo_mysql iconv && \
    docker-php-ext-enable iconv intl opcache pdo_mysql && \
    rm -Rf /var/www/html; \
    symfony check:requirements

COPY etc/php/conf.d/php.ini  /usr/local/etc/php/conf.d/


#>docker-php-ext-install
#bcmath bz2 calendar ctype curl dba dl_test dom enchant exif ffi fileinfo filter ftp gd gettext gmp hash iconv imap intl
#json ldap mbstring mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite
#pgsql phar posix pspell readline reflection session shmop simplexml snmp soap sockets sodium spl standard sysvmsg sysvsem
#sysvshm tidy tokenizer xml xmlreader xmlwriter xsl zend_test zip

#    && docker-php-ext-configure gd --with-freetype --with-jpeg \
#    && docker-php-ext-install -j$(nproc) gd pdo_mysql iconv ctype pcre simplexml \
