# 1. Base ligera de PHP 8.4 (Requerido por Laravel 13)
FROM php:8.4-fpm-alpine

# 2. Dependencias del sistema (Sin Postgres para ahorrar 400MB de RAM/Espacio)
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

# 3. Extensiones PHP esenciales para MySQL y Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# 4. Traemos Composer (Versión estable)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Copiamos los archivos del proyecto
# IMPORTANTE: Asegúrate de NO subir el composer.lock viejo si está corrupto
COPY . .

# 6. INSTALACIÓN OPTIMIZADA (Para evitar el Exit Code 4 de Memoria)
ENV COMPOSER_ALLOW_SUPERUSER=1

# Forzamos un límite de RAM para que Composer no sature el contenedor de Render
RUN php -d memory_limit=350M /usr/bin/composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-cache

# 7. Generamos el autoloader ignorando requisitos de plataforma (Evita Error 1)
RUN php -d memory_limit=350M /usr/bin/composer dump-autoload --no-dev --ignore-platform-reqs

# 8. Permisos para carpetas de sistema
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Configuración rápida de Nginx
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

# 10. ARRANQUE RESILIENTE:
# Si el DNS falla un segundo al conectar con Aiven, el "|| true" permite que el servidor suba igual.
CMD (php artisan config:clear || true) && (php artisan cache:clear || true) && php-fpm -D && nginx -g "daemon off;"
