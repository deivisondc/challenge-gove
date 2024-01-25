FROM php:8.2

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN cp .env.example .env

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
