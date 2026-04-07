# 1. Imagen base
FROM richarvey/nginx-php-fpm:latest

# 2. Directorio estándar del servidor web
WORKDIR /var/www/html

# 3. Copiamos archivos (SIN la palabra backend, porque ya estamos ahí)
COPY composer.json composer.lock ./

# 4. Instalamos dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 5. Copiamos todo el código de la carpeta actual
COPY . .

# 6. Formato corregido (usando =) para que Railway no se queje
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 7. Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 8. Comando de arranque
CMD php artisan route:cache && php artisan migrate --force && /start.sh
