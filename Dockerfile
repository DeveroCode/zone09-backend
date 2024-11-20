# Etapa 1: Construcción de la imagen para Laravel
FROM php:8.2-fpm AS base

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de la aplicación
COPY . .

# Copiar el script de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Configurar el script como comando predeterminado
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]

RUN chmod +x /usr/local/bin/entrypoint.sh

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar puerto de escucha
EXPOSE 1000

# Comando de inicio
CMD ["php-fpm"]
