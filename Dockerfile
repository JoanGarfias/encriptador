FROM composer:2.7 AS vendor
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader

FROM node:20-alpine AS frontend
WORKDIR /app
ARG VITE_APP_URL
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN VITE_APP_URL=${VITE_APP_URL} npm run build

FROM php:8.3-fpm-alpine
WORKDIR /var/www/html

RUN apk add --no-cache \
        libzip-dev \
        libpng-dev \
        jpeg-dev \
        freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd exif

COPY . .

COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend /app/public/build/ /var/www/html/public/build/

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]
