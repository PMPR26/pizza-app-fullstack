FROM richarvey/nginx-php-fpm:latest

# Directorio de trabajo en el contenedor
WORKDIR /var/www/html

# 1. Copiamos los archivos (Ya estamos dentro de 'backend', así que usamos '.')
COPY composer.json composer.lock ./

# 2. Instalamos dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 3. Copiamos todo lo demás
COPY . .

# 4. Variables de entorno con formato '='
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 5. Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 6. Ejecutamos caché y arrancamos
CMD php artisan route:cache && php artisan migrate --force && /start.sh
