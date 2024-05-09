# Use an official PHP runtime as a parent image
FROM php:8.1-fpm
# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Set working directory
WORKDIR /var/www
# Copy existing application directory contents
COPY . /var/www
# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Ensure the correct permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy the Nginx configuration
COPY ./nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm