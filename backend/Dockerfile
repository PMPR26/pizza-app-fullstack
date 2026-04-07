FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# 1. Copiamos TODO primero (incluyendo artisan y las carpetas)
COPY . .

# 2. Ahora sí, instalamos (ya con artisan presente)
# Añadimos la variable para que Composer no se queje de ser root
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# 3. Configuramos variables de entorno restantes
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1

# 4. Permisos finales
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 5. Comando de arranque
CMD php artisan route:cache && php artisan migrate --force && /start.sh
