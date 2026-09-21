#!/usr/bin/env bash

echo "Running composer..."
composer install --no-dev --working-dir=/var/www/html

echo "Clearing cache..."
php artisan optimize:clear

echo "===== ROUTES ====="
php artisan route:list
echo "=================="

echo "Running migrations..."
php artisan migrate --force