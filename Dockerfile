FROM php:8.4-fpm

WORKDIR /app

# Force fresh build 2026-07-26 v3

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    python3 \
    python3-venv \
    python3-pip \
    nodejs \
    && docker-php-ext-install zip pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN python3 -m venv /app/.venv && \
    . /app/.venv/bin/activate && \
    pip install -r /app/python/requirements.txt --quiet

RUN composer install --optimize-autoloader --no-interaction && \
    php artisan route:cache && \
    php artisan view:cache

RUN npm ci && npm run build

RUN chmod -R 775 /app/storage /app/bootstrap/cache && \
    chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["bash", "start.sh"]
