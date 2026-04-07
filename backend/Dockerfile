FROM richarvey/nginx-php-fpm:latest

# Directorio de trabajo en el contenedor
WORKDIR /var/www/html

# 1. Copiamos todo el contenido del backend al contenedor
COPY . .

# 2. Configuraciones de entorno para Composer y Nginx
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# 3. Instalamos dependencias de PHP
# Usamos --ignore-platform-reqs para evitar conflictos de versiones en el build
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# 4. Aseguramos permisos correctos para Laravel
# Esto evita errores de escritura en logs y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 5. Exponemos el puerto 80
EXPOSE 80

# 6. COMANDO FINAL: Migraciones + Caché + Arranque
# Usamos 'sh -c' para encadenar los comandos correctamente al iniciar el contenedor
CMD sh -c "php artisan migrate --force && php artisan route:cache && php artisan config:cache && /start.sh"
