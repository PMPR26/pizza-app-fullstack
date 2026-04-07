# Base image
FROM richarvey/nginx-php-fpm:latest

# Set workdir
WORKDIR /var/www/html

# 1. Copiamos los archivos directamente (porque el Root es 'backend')
COPY composer.json composer.lock ./

# 2. Instalamos dependencias
RUN composer install --optimize-autoloader --no-dev

# 3. Copiamos todo lo demás de la carpeta backend
COPY . .

# 4. Corregimos el formato de las variables (como pidió el log)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# El comando de arranque que incluye tus rutas y migraciones
CMD php artisan route:cache && php artisan migrate --force && /start.sh
