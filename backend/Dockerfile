FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# 1. Copiamos especificando la ruta desde la raíz del repo
COPY backend/composer.json backend/composer.lock ./

# 2. Instalamos dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 3. Copiamos todo el contenido de la carpeta backend al contenedor
COPY backend/. .

# 4. Variables con formato '=' (Para quitar los errores del log)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 5. Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 6. Comando de arranque: Caché + Migraciones + Script de la imagen
CMD php artisan route:cache && php artisan migrate --force && /start.sh
