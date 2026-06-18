#!/usr/bin/env bash

# Optimizar la caché de Laravel para producción
echo "Caching configuration..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

# Ejecutar las migraciones de la base de datos de forma automática
echo "Running migrations..."
php artisan migrate --force
