#!/bin/bash

# Ejecutar migraciones
php artisan migrate --force

# Iniciar PHP-FPM
php-fpm
