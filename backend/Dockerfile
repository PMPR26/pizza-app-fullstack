# 1. Base ligera
FROM php:8.3-fpm-alpine

# 2. Dependencias del sistema (LIMPIEZA TOTAL: SIN POSTGRES)
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

# 3. Extensiones PHP (SOLO MYSQL PARA AHORRAR RAM)
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# 4. Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Copiamos los archivos
COPY . .

# 6. INSTALACIÓN DE EMERGENCIA CON LÍMITE DE MEMORIA
ENV COMPOSER_ALLOW_SUPERUSER=1

# Forzamos a PHP a usar un límite de memoria bajo para que no choque con Render
# Y desactivamos el caché de Composer para ahorrar RAM durante el build
RUN php -d memory_limit=256M /usr/bin/composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-cache

RUN php -d memory_limit=256M /usr/bin/composer dump-autoload --no-dev

# 7. Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Nginx
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

CMD php artisan config:clear && php artisan cache:clear && php-fpm -D && nginx -g "daemon off;"
