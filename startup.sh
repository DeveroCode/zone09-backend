#!/bin/bash

# Esperar a que la base de datos esté disponible
echo "Esperando a que la base de datos esté disponible..."
while ! nc -z ${DB_HOST} ${DB_PORT}; do
  sleep 1
done
echo "Base de datos disponible, comenzando migraciones..."

# Ejecutar las migraciones
php artisan migrate --force

# Ejecutar los seeders
php artisan db:seed --force

# Iniciar PHP-FPM
php-fpm
