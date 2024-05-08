#!/usr/bin/env bash

# Ensure the required PHP version is installed
apt-get update -y
apt-get install -y php-cli php-mbstring php-xml unzip curl

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies via Composer
composer install --no-dev --optimize-autoloader
