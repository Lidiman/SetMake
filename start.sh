#!/bin/bash
set -e

echo "=== Activating Python venv ==="
source /app/.venv/bin/activate

echo "=== Setting up Laravel ==="
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link --force || true

echo "=== Starting PHP server on port $PORT ==="
php artisan serve --host=0.0.0.0 --port=$PORT
