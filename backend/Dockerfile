# Usamos PHP 8.3 con FPM (la versión que pide tu código)
FROM php:8.3-fpm-alpine

# Instalamos extensiones necesarias para Laravel y el servidor Nginx
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    postgresql-dev

# Instalamos las extensiones de PHP para PostgreSQL y strings
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Instalamos Composer de la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiamos los archivos del proyecto
COPY . .

# Instalamos dependencias de Laravel ignorando restricciones de plataforma
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Permisos para las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración básica de Nginx (para que escuche en el puerto 80)
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

# Comando de arranque: Inicia PHP-FPM y Nginx al mismo tiempo
CMD php-fpm -D && nginx -g "daemon off;"
