#!/bin/sh

# Exit on error
set -e

echo "Starting Laravel application..."

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
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

