FROM php:8.2-apache

# Cài MySQL PDO driver
RUN docker-php-ext-install pdo pdo_mysql

# Enable rewrite (nếu dùng routing)
RUN a2enmod rewrite

# Copy source code
COPY . /var/www/html/

WORKDIR /var/www/html/

EXPOSE 80
