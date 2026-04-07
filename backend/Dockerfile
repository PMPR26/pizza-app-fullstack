FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# Copiamos usando la ruta desde la raíz del repo
COPY backend/composer.json backend/composer.lock ./

RUN composer install --no-interaction --optimize-autoloader --no-dev

COPY backend/. .

# Variables con formato "=" para evitar los warnings del log
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Comando de arranque
CMD php artisan route:cache && php artisan migrate --force && /start.sh
