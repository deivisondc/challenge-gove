FROM php:8.2

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# RUN touch test.php
# RUN echo "<?php var_dump(extension_loaded('zip'));" >> test.php

# CMD ["php", "test.php"]

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
