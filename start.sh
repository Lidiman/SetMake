#!/bin/bash
set -e

echo "=== Installing Python dependencies ==="
python3 -m venv /app/.venv
source /app/.venv/bin/activate
pip install -r /app/python/requirements.txt --quiet

echo "=== Setting up Laravel ==="
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link --force

echo "=== Starting PHP server ==="
php artisan serve --host=0.0.0.0 --port=$PORT
