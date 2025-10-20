FROM php:8.3-fpm-alpine AS builder

RUN apk add --no-cache \
        composer \
        nodejs \
        npm \
        libzip-dev \
        libpng-dev \
        jpeg-dev \
        freetype-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd exif session fileinfo tokenizer dom

WORKDIR /app

COPY . .

RUN composer install --no-dev --no-interaction --optimize-autoloader

ARG VITE_APP_URL
RUN npm install
RUN VITE_APP_URL=${VITE_APP_URL} npm run build

RUN rm -rf node_modules

FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
        libzip-dev \
        libpng-dev \
        jpeg-dev \
        freetype-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd exif session fileinfo tokenizer dom

COPY --from=builder /app /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]
