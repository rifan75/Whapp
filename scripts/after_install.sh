#!/bin/bash

# Copy the production environment file from S3 to the local installation
aws s3 cp s3://warehouse-env/.warehouse_demo_env /var/www/html/warehouse-demo/.env

# Setup the various file and folder permissions for Laravel
chown -R ubuntu:www-data /var/www/html/warehouse-demo
find /var/www/html/warehouse-demo -type d -exec chmod 755 {} +
find /var/www/html/warehouse-demo -type f -exec chmod 644 {} +
chgrp -R www-data /var/www/html/warehouse-demo/storage /var/www/html/warehouse-demo/bootstrap/cache
chmod -R ug+rwx /var/www/html/warehouse-demo/storage /var/www/html/warehouse-demo/bootstrap/cache

# Download Vendor
composer install -d /var/www/html/warehouse-demo --no-plugins --no-scripts
composer update -d /var/www/html/warehouse-demo --no-plugins --no-scripts

# Clear any previous cached views and optimize the application
php /var/www/html/warehouse-demo/artisan storage:link
php /var/www/html/warehouse-demo/artisan cache:clear
php /var/www/html/warehouse-demo/artisan view:clear
php /var/www/html/warehouse-demo/artisan config:cache
php /var/www/html/warehouse-demo/artisan optimize
php /var/www/html/warehouse-demo/artisan route:cache
