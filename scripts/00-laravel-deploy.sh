#!/usr/bin/env bash

echo "Running composer..."
composer install --no-dev --working-dir=/var/www/html

echo "Ensuring storage directories exist..."
mkdir -p storage/framework/{cache,sessions,testing,views} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "Clearing cache..."
php artisan optimize:clear

echo "===== ROUTES ====="
php artisan route:list
echo "=================="

echo "Running migrations..."
php artisan migrate --force