FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# 1. Copiamos los archivos que están en la raíz del contexto actual
COPY composer.json composer.lock ./

# 2. Instalamos dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 3. Copiamos todo lo que hay en la carpeta actual
COPY . .

# 4. Corregimos el formato de variables para que el log no se queje
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 5. Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 6. Ejecutamos caché, migraciones y arrancamos
CMD php artisan route:cache && php artisan migrate --force && /start.sh
