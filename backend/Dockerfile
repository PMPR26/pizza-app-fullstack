FROM richarvey/nginx-php-fpm:latest

# Directorio de trabajo interno (No lo cambies, es el estándar de la imagen)
WORKDIR /var/www/html

# 1. Copiamos desde la carpeta backend del REPO a la raíz del CONTENEDOR
COPY backend/composer.json backend/composer.lock ./

# 2. Instalamos dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 3. Copiamos todo el contenido de la carpeta backend
COPY backend/. .

# 4. Variables con formato '=' (Correcto para Docker moderno)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# 5. Permisos para que Laravel no explote
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 6. Ejecutamos caché, migraciones y arrancamos
CMD php artisan route:cache && php artisan migrate --force && /start.sh
