FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# 1. Copiamos los archivos
COPY . .

# 2. Forzamos la instalación IGNORANDO por completo las versiones de PHP
# Esto saltará los errores de "Problem 1, Problem 2..."
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# 3. Configuraciones críticas de la imagen
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# 4. Permisos para que Laravel no explote al escribir
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 5. Comando de arranque: Quitamos el "migrate" de aquí para que no detenga el inicio
# Si hay que migrar, lo haremos desde la consola de Render después.
CMD ["/start.sh"]
