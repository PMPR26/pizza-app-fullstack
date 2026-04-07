FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# 1. Copiamos los archivos de composer (SIN la palabra backend)
COPY composer.json composer.lock ./

# 2. Instalamos dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 3. Copiamos TODO lo que hay en la carpeta actual (.) al contenedor (.)
COPY . .

# 4. Variables con formato correcto (=) para evitar warnings
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 5. Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 6. Comando de arranque: Caché + Migraciones + Script de la imagen
CMD php artisan route:cache && php artisan migrate --force && /start.sh
