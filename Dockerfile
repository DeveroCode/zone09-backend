# Usa una imagen base de PHP con Composer preinstalado
FROM php:8.3.8-fpm

# Instala las dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo
WORKDIR /app

# Copia los archivos de la aplicación
COPY . /app

# Instala las dependencias de PHP y Node.js
RUN composer install && npm install --production && npm install

# Optimiza la aplicación
RUN php artisan optimize && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --seed --force

# Exponer el puerto 8080
EXPOSE 8080

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
