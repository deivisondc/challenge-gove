FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql zip gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer install

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
