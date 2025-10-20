FROM node:20-alpine AS frontend_deps

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install


FROM composer:2 AS vendor

WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --optimize-autoloader


FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
        nginx \
        supervisor \
        nodejs \
        npm \
        libzip-dev \
        libpng-dev \
        jpeg-dev \
        freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd exif

COPY . .

COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend_deps /app/node_modules/ /var/www/html/node_modules/

RUN npm run build

RUN rm -rf node_modules

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]
