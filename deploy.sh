#!/bin/bash

# Navigate to the Laravel project directory
cd $HOME/public_html || exit 1

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --prefer-dist

# Set file permissions for storage and cache directories
echo "Setting file permissions..."
chown -R user:user storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Clear and cache Laravel config, routes, and views
echo "Clearing and caching config, routes, and views..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Optimize the Laravel app
echo "Optimizing the application..."
php artisan optimize:clear
php artisan optimize

# Clear any old caches
echo "Clearing old caches..."
php artisan cache:clear

echo "Deployment completed successfully!"
