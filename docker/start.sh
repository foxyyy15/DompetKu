#!/usr/bin/env bash
set -e

PORT="${PORT:-80}"

# Configure Apache to listen on Render's dynamic $PORT
sed -i -E "s/^Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Cache Laravel config, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations automatically on deploy
php artisan migrate --force

# Start Apache in the foreground
exec apache2-foreground
