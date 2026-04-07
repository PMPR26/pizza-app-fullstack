# 1. Subimos a PHP 8.4 (Tus dependencias de Laravel 13 lo requieren)
FROM php:8.4-fpm-alpine

# 2. Dependencias del sistema (LIMPIEZA TOTAL: SIN POSTGRES PARA AHORRAR RAM)
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

# 3. Extensiones PHP (SOLO MYSQL PARA AHORRAR MEMORIA)
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# 4. Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Copiamos los archivos del proyecto
COPY . .

# 6. INSTALACIÓN DE EMERGENCIA (IGNORANDO CONFLICTOS DE PLATAFORMA)
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalación optimizada para los 512MB de Render
RUN php -d memory_limit=256M /usr/bin/composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-cache

# 7. Generamos el autoloader (Añadimos ignore-platform-reqs para evitar el Error 1)
RUN php -d memory_limit=256M /usr/bin/composer dump-autoload --no-dev --ignore-platform-reqs

# 8. Permisos CRÍTICOS para evitar Errores 500
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Configuración de Nginx
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

# 10. Comando de arranque: Limpieza de caché antes de iniciar
CMD php artisan config:clear && php artisan cache:clear && php-fpm -D && nginx -g "daemon off;"
