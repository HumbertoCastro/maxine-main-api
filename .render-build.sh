#!/usr/bin/env bash

# Install required packages
apt-get update -y
apt-get install -y software-properties-common curl unzip

# Add the PHP repository
add-apt-repository ppa:ondrej/php -y
apt-get update -y

# Install PHP and necessary extensions
apt-get install -y php8.0 php8.0-cli php8.0-mbstring php8.0-xml php8.0-zip php8.0-curl

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies via Composer
composer install --no-dev --optimize-autoloader
