# Stage 1: Build dependencies with Composer
FROM composer:2 as composer
WORKDIR /app


# Stage 2: PHP-Apache base image
FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y unzip git curl && \
    docker-php-ext-install pdo pdo_mysql mysqli && \
    a2enmod rewrite

# Copy all application files
COPY . /var/www/html/

# Copy vendor from builder stage

# Enable .htaccess override
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html
WORKDIR /var/www/html

