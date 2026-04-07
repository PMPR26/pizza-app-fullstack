# Base image con PHP + Nginx + FPM
FROM richarvey/nginx-php-fpm:latest

# Set workdir
WORKDIR /var/www/html

# 1. Copiamos los archivos de composer (SIN prefijo backend/)
COPY composer.json composer.lock ./

# 2. Instalar dependencias de PHP
RUN composer install --optimize-autoloader --no-dev

# 3. Copiar el resto del contenido de la carpeta backend
COPY . .

# 4. Variables de entorno (Formato corregido con =)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 5. Permisos necesarios para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 80

# 6. Comando de inicio (Ejecuta Laravel y luego el script de la imagen)
CMD php artisan route:cache && php artisan migrate --force && /start.sh
