FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache git curl libpng libpng-dev libjpeg-turbo-dev libwebp-dev zlib-dev libxpm-dev gd-dev zip libzip-dev
# Install Node.js (and npm)
RUN apk add --update nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql gd zip exif pcntl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Expose port 9000 and start php-fpm server
CMD ["php-fpm"]
