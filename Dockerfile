FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-scripts

COPY . .

RUN composer dump-autoload

CMD ["php-fpm"]
