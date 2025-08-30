FROM composer:2 AS build

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev git unzip \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www

COPY --from=build /app ./

RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php-fpm"]
