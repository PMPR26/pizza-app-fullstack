# Base image con PHP + Nginx + FPM
FROM richarvey/nginx-php-fpm:latest

# Set workdir en backend
WORKDIR /var/www/html

# Copiar solo composer files primero para cache
COPY backend/composer.json backend/composer.lock ./

# Instalar dependencias de PHP
RUN composer install --optimize-autoloader --no-dev

# Copiar el resto del backend
COPY backend/. .

# Variables de entorno
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV REAL_IP_HEADER 1
ENV COMPOSER_ALLOW_SUPERUSER 1

# Exponer puerto si es necesario
EXPOSE 80

# Comando por defecto
CMD ["/start.sh"]
