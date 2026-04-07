FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# 1. Copiamos especificando la carpeta backend
COPY backend/composer.json backend/composer.lock ./

# 2. Instalamos dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 3. Copiamos todo el contenido de la carpeta backend al contenedor
COPY backend/. .

# 4. Variables de entorno (Formato corregido)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 5. Permisos críticos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 6. Ejecutamos caché y arrancamos el servidor
CMD php artisan route:cache && php artisan migrate --force && /start.sh
