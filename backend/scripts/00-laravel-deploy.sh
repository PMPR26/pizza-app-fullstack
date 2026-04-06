#!/usr/bin/env bash

echo "Installing composer dependencies..."
composer install --no-dev --working-dir=/var/www/html

echo "Generating cache..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force
