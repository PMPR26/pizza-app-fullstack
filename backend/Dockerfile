# 1. Usamos una imagen estable y ligera
FROM php:8.2-fpm-alpine

# 2. Instalamos extensiones necesarias para Laravel y MySQL
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    && docker-php-ext-install pdo_mysql gd zip

# 3. Instalamos Composer
COPY --from=composer:latest /usr/local/bin/composer /usr/local/bin/composer

# 4. Directorio de trabajo oficial
WORKDIR /var/www/html

# 5. COPIAR ARCHIVOS (Truco de Ingeniera: Usamos el contexto del Root Directory)
# Si en Railway pusiste Root Directory: backend, usamos "."
COPY . .

# 6. Instalar dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 7. Permisos críticos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/cache

# 8. Exponer el puerto que Railway asigna
EXPOSE 8080

# 9. EL COMANDO FINAL (Todo en uno)
CMD php artisan route:cache && php artisan migrate --force && php artisan serve --host 0.0.0.0 --port $PORT
