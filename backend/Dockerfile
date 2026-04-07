FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# Copiar archivos de composer (las rutas son relativas al root directory /backend)
COPY composer.json composer.lock ./

RUN composer install --no-interaction --optimize-autoloader --no-dev

# Copiar todo el código
COPY . .

# Variables de entorno - CORREGIDAS (usando formato key=value)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Comando de inicio
CMD php artisan route:cache && php artisan migrate --force && /start.sh
