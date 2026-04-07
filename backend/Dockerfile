# 1. Base ligera de PHP 8.4 (Soporta Laravel 13)
FROM php:8.4-fpm-alpine

# 2. Dependencias del sistema (Limpieza total para ahorrar RAM)
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    mariadb-client \
    linux-headers \
    $PHPIZE_DEPS

# 3. Extensiones PHP para MySQL y procesamiento de datos
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# 4. Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Copiamos el proyecto
# Recordatorio: Borra el composer.lock viejo antes de hacer el push
COPY . .

# 6. INSTALACIÓN DE BAJO CONSUMO (Evita Error 4 de Memoria)
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN php -d memory_limit=350M /usr/bin/composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-cache

# 7. Generamos autoloader sin colapsar la RAM
RUN php -d memory_limit=350M /usr/bin/composer dump-autoload --no-dev --ignore-platform-reqs

# 8. Permisos de carpetas
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Configuración de Nginx (Servidor Web)
RUN printf "server { \n\
    listen 80; \n\
    root /var/www/html/public; \n\
    index index.php index.html; \n\
    location / { \n\
        try_files \$uri \$uri/ /index.php?\$query_string; \n\
    } \n\
    location ~ \.php$ { \n\
        include fastcgi_params; \n\
        fastcgi_pass 127.0.0.1:9000; \n\
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \n\
    } \n\
}" > /etc/nginx/http.d/default.conf

EXPOSE 80

# 10. ARRANQUE MAESTRO (Web + Colas)
# - Limpia caché (ignora errores de red iniciales con || true)
# - Ejecuta el Worker en segundo plano (&) para procesar tus Jobs
# - Arranca Nginx y PHP-FPM
CMD (php artisan config:clear || true) && \
    (php artisan cache:clear || true) && \
    (php artisan queue:work --daemon --tries=3 --quiet &) && \
    php-fpm -D && \
    nginx -g "daemon off;"
