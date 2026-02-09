FROM php:8.2-apache

# Instalar extensión MySQL para PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar código fuente al servidor web
COPY src/ /var/www/html/

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Asignar permisos correctos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
