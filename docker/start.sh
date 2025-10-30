#!/bin/bash

# Exit on error
set -e

echo "========================================="
echo "Starting Laravel application..."
echo "========================================="

# Create necessary directories
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/log/supervisor

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for database (if needed)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database..."
    timeout=30
    while ! nc -z $DB_HOST ${DB_PORT:-3306} 2>/dev/null; do
        timeout=$((timeout - 1))
        if [ $timeout -le 0 ]; then
            echo "Database connection timeout!"
            exit 1
        fi
        echo "Waiting for database... ($timeout seconds remaining)"
        sleep 1
    done
    echo "Database is ready!"
fi

# Run migrations (uncomment if needed)
# php artisan migrate --force --no-interaction || echo "Migration failed, continuing..."

# Clear and cache config
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "Caching Laravel configurations..."
php artisan config:cache || echo "Config cache failed, continuing..."
php artisan route:cache || echo "Route cache failed, continuing..."
php artisan view:cache || echo "View cache failed, continuing..."

# Test PHP-FPM configuration
echo "Testing PHP-FPM configuration..."
php-fpm -t || { echo "PHP-FPM config test failed!"; exit 1; }

# Test Nginx configuration
echo "Testing Nginx configuration..."
nginx -t || { echo "Nginx config test failed!"; exit 1; }

echo "========================================="
echo "All tests passed! Starting services..."
echo "PHP-FPM will listen on 127.0.0.1:9000"
echo "Nginx will listen on 0.0.0.0:10000"
echo "========================================="

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

