FROM php:8.3-fpm-alpine

# 1. Instalamos librerías del sistema
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
    mariadb-client

# 2. Instalamos extensiones de PHP (MySQL es la clave aquí)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring gd zip

# 3. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Copiamos los archivos del proyecto
COPY . .

# 5. Instalamos dependencias de PHP
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# 6. Permisos absolutos para evitar el Error 500
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Configuración de Nginx
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

# 8. Comando de arranque: Limpiamos cualquier caché residual antes de iniciar
CMD php artisan config:clear && php artisan cache:clear && php-fpm -D && nginx -g "daemon off;"
