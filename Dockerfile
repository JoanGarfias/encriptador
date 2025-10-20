# --- ETAPA 1: "BUILDER" (La Fábrica con todas las herramientas) ---
# Empezamos con una imagen de PHP y le añadimos Node.js
FROM php:8.3-fpm-alpine AS builder

# Instalar dependencias del sistema: Node.js y extensiones de PHP (sin PHP de Alpine)
RUN apk add --no-cache \
        nodejs \
        npm \
        libzip-dev \
        libpng-dev \
        jpeg-dev \
        freetype-dev \
        libxml2-dev \
    && docker-php-source extract \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql zip gd exif dom opcache \
    && docker-php-source delete

# Composer del contenedor oficial (para usar /usr/local/bin/php)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

# Copiar todo el código de la aplicación a la fábrica
COPY . .

# Forzar que la app use las variables de entorno de Docker en lugar de un archivo .env horneado en la imagen
RUN rm -f .env

# 1. Instalar dependencias de Composer (ahora tiene acceso a 'artisan')
RUN composer install --no-dev --no-interaction --optimize-autoloader

# 2. Instalar dependencias de Node y construir los assets
#    Necesita un argumento 'VITE_APP_URL' que pasaremos desde docker-compose
ARG VITE_APP_URL
RUN npm install
RUN VITE_APP_URL=${VITE_APP_URL} npm run build

# 3. Limpiar los archivos de desarrollo que no necesitamos en producción
RUN rm -rf node_modules

# --- ETAPA 2: "PRODUCTION" (La imagen final, limpia y ligera) ---
# Empezamos de nuevo con una imagen limpia de PHP
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Instalar solo las extensiones de PHP que la app necesita para correr
RUN apk add --no-cache \
        libzip-dev \
        libpng-dev \
        jpeg-dev \
        freetype-dev \
        libxml2-dev \
    && docker-php-source extract \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql zip gd exif dom opcache \
    && docker-php-source delete

# Copiar la aplicación ya construida desde la etapa "builder"
COPY --from=builder /app /var/www/html

# Establecer permisos correctos para que Laravel pueda escribir en sus carpetas
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# El comando que se ejecuta cuando el contenedor arranca
CMD ["php-fpm"]
