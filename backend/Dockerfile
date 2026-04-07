FROM richarvey/nginx-php-fpm:latest

# El lugar donde vive el código en el servidor
WORKDIR /var/www/html

# Copiamos TODO lo que hay donde estamos parados
COPY . .

# Variables corregidas (con el signo =)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalamos dependencias rápido
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Comando de arranque (Caché + Servidor)
CMD php artisan route:cache && php artisan migrate --force && /start.sh
