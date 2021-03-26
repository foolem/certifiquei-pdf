FROM php:7.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache libpng libpng-dev && docker-php-ext-install gd && apk del libpng-dev

RUN docker-php-ext-install pdo pdo_mysql
