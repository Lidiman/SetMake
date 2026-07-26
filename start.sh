#!/bin/bash
set -e

echo "=== Activating Python venv ==="
source /app/.venv/bin/activate

echo "=== Setting up Laravel ==="
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link --force

echo "=== Starting services ==="
nginx -c /app/docker/nginx.conf

php-fpm -F
