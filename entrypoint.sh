#!/bin/bash

# Ejecutar migraciones y capturar errores
if ! php artisan migrate --force; then
    echo "Error: Las migraciones no se ejecutaron correctamente."
    exit 1
fi

# Iniciar PHP-FPM
php-fpm
