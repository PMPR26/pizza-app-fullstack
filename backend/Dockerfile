# 1. Usamos PHP 8.3 con FPM sobre Alpine (Ligero)
FROM php:8.3-fpm-alpine

# 2. Instalamos librerías del sistema necesarias
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

# 3. Instalamos extensiones de PHP (Incluimos bcmath para Laravel 11/13)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring gd zip bcmath pcntl

# 4. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Copiamos los archivos del proyecto
COPY . .

# 6. Instalación de dependencias OPTIMIZADA PARA POCA RAM (Evita Exit Code 4)
ENV COMPOSER_ALLOW_SUPERUSER=1
# Instalamos primero sin optimizar el autoloader para no saturar la memoria de Render
RUN composer install --no-interaction --no-dev --ignore-platform-reqs

# Generamos el autoloader optimizado en un paso separado para ahorrar RAM
RUN composer dump-autoload --optimize --no-dev

# 7. Permisos CRÍTICOS para evitar Errores 500
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

# 9. Comando de arranque: Limpiamos caché antes de iniciar
CMD php artisan config:clear && php artisan cache:clear && php-fpm -D && nginx -g "daemon off;"
