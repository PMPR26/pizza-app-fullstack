# 1. Imagen base oficial de PHP
FROM php:8.2-fpm-alpine

# 2. Dependencias del sistema para Laravel y MySQL
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    && docker-php-ext-install pdo_mysql gd zip

# 3. Instalar Composer desde la imagen oficial
COPY --from=composer:latest /usr/local/bin/composer /usr/local/bin/composer

# 4. Establecer el directorio de trabajo (Ruta estándar de Linux)
WORKDIR /var/www/html

# 5. Copiar los archivos (Como el Root Directory es 'backend', copiamos todo lo de esa carpeta)
COPY . .

# 6. Instalar las dependencias de Laravel (Esto DEBE tardar más de 30 segundos)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 7. Permisos de escritura para que Laravel no explote
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Exponer el puerto
EXPOSE 8080

# 9. COMANDO DE ARRANQUE (Sin el /app/ porque el WORKDIR es /var/www/html)
CMD php artisan route:cache && php artisan migrate --force && php artisan serve --host 0.0.0.0 --port $PORT
