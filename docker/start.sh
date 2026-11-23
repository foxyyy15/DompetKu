#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

# Cache Laravel config, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations automatically on deploy
php artisan migrate --force

# Start Laravel server bound to Railway's $PORT
exec env PHP_CLI_SERVER_WORKERS=4 php artisan serve --host=0.0.0.0 --port="${PORT}"
