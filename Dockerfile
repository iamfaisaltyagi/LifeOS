FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y git unzip libzip-dev libpq-dev
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000
CMD php artisan config:clear && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=10000