# 1. Base ligera de Alpine
FROM php:8.3-fpm-alpine

# 2. Dependencias del sistema (Instalación limpia)
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    postgresql-dev \
    mariadb-client \
    linux-headers \
    $PHPIZE_DEPS

# 3. Extensiones PHP (Solo las estrictamente necesarias para Laravel)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring gd zip bcmath

# 4. Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Copiamos los archivos del proyecto
COPY . .

# 6. INSTALACIÓN DE EMERGENCIA (Evita el Error 4 de Memoria)
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalamos sin optimizar, sin plugins y sin scripts para ahorrar cada MB de RAM
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --ignore-platform-reqs

# Generamos el autoloader estándar (No optimizado para no saturar la RAM)
RUN composer dump-autoload --no-dev

# 7. Permisos absolutos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Configuración de Nginx
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

# 9. Arranque: Limpieza de caché residual
CMD php artisan config:clear && php artisan cache:clear && php-fpm -D && nginx -g "daemon off;"
