# Web tier: Apache + PHP with the mysqli extension and the application code baked in.
FROM php:8.2-apache

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY app/ /var/www/html/

EXPOSE 80
