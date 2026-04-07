FROM richarvey/nginx-php-fpm:latest

# Directorio de trabajo estándar
WORKDIR /var/www/html

# Copiamos los archivos de composer primero para aprovechar el caché
COPY composer.json composer.lock ./

# Instalamos dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs
# Copiamos TODO el contenido de la carpeta actual (backend)
COPY . .

# Variables de entorno necesarias
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Comando de arranque
CMD php artisan route:cache && php artisan migrate --force && /start.sh
