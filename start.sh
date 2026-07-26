#!/bin/bash

echo "=== Activating Python venv ==="
source /app/.venv/bin/activate

echo "=== Setting up Laravel ==="
php artisan key:generate --force

echo "=== Waiting for database ==="
for i in {1..30}; do
    php artisan migrate --force --no-interaction && break
    echo "  DB not ready, retrying in 2s... ($i/30)"
    sleep 2
done

php artisan storage:link --force 2>/dev/null || true

echo "=== Starting PHP server on port $PORT ==="
php artisan serve --host=0.0.0.0 --port=$PORT
