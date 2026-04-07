FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# Copiar archivos de composer primero (mejor para caché)
COPY composer.json composer.lock ./

# Instalar dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# Copiar el resto del código
COPY . .

# Ejecutar scripts de composer después de copiar todo
RUN composer run-script post-autoload-dump --no-interaction

# Variables de entorno
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# Crear directorios necesarios y permisos
RUN mkdir -p /var/www/html/storage/framework/{sessions,views,cache} \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/start.sh"]
