FROM php:8.2-apache

RUN docker-php-ext-install mysqli && chown -R www-data:www-data /var/www/html

COPY src/ /var/www/html/

EXPOSE 80
