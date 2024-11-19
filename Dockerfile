# 1. Usar una imagen base de PHP con FPM
FROM php:8.3-fpm

# 2. Instalar las dependencias del sistema necesarias para PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# 3. Instalar Composer (gestor de dependencias de PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. Establecer el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# 5. Copiar los archivos de tu proyecto al contenedor
COPY . .

# 6. Copiar el archivo .env
COPY .env .env

# 7. Instalar las dependencias de PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# 8. Ejecutar los comandos de optimización de Laravel (sin migrar ni seeders)
RUN php artisan optimize \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# 9. Asegurarse de que las carpetas de Laravel tengan los permisos adecuados
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Exponer el puerto en el que PHP-FPM estará escuchando
EXPOSE 9000

# 11. Comando por defecto para ejecutar PHP-FPM
CMD ["php-fpm"]

# Copiar el script de inicio
COPY startup.sh /usr/local/bin/startup.sh

# Darle permisos de ejecución
RUN chmod +x /usr/local/bin/startup.sh

# Usar el script como comando principal
CMD ["/usr/local/bin/startup.sh"]

