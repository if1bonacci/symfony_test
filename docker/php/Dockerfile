FROM php:8.2-fpm

RUN apt update \
    && apt install libxml2-dev -y zlib1g-dev openssh-client unzip g++ libicu-dev libpng-dev \
    libzip-dev zip mc \
    telnet curl\
    && docker-php-ext-install intl opcache pdo sockets zip soap gd pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

COPY ./ext/docker-php-ext-opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

WORKDIR /var/www/symfony
