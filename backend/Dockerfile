FROM richarvey/nginx-php-fpm:latest

# El lugar donde vivirá el código dentro del contenedor
WORKDIR /var/www/html

# 1. Copiamos los archivos de composer desde la carpeta backend del repo
COPY backend/composer.json backend/composer.lock ./

# 2. Instalamos dependencias (Esto debe tardar un poco, indicando que funciona)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 3. Copiamos el resto del código de la carpeta backend
COPY backend/. .

# 4. Variables con formato '=' para que el log salga limpio (sin amarillos)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 5. Permisos para que Laravel no dé error 500
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 6. Comando de arranque: Caché + Migraciones + Script de la imagen
CMD php artisan route:cache && php artisan migrate --force && /start.sh
