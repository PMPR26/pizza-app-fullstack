FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# Usamos el punto (.) porque el Root Directory ya es 'backend'
COPY composer.json composer.lock ./

RUN composer install --optimize-autoloader --no-dev

COPY . .

# Formato corregido con el signo '=' para evitar errores de sintaxis
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD php artisan route:cache && php artisan migrate --force && /start.sh
