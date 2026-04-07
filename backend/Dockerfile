FROM php:8.3-fpm-alpine

# Instalamos extensiones necesarias
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

# Instalamos drivers de base de datos
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring gd zip

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Optimizamos Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# --- LO QUE FALTABA: CONFIGURACIÓN DE LARAVEL ---
# Creamos un .env base si no existe para que Laravel no explote
RUN cp .env.example .env || touch .env

# Generamos las cachés de Laravel para mejorar velocidad y evitar errores 500
# (Nota: La APP_KEY debe estar en las variables de Render)
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache
# -----------------------------------------------

# Permisos CRÍTICOS para evitar el Error 500
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración Nginx
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
CMD php-fpm -D && nginx -g "daemon off;"
