#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Tlaix..."

# Esperar a que la base de datos esté lista
echo "⏳ Esperando conexión a base de datos..."
until php artisan db:show 2>/dev/null; do
    echo "Base de datos no disponible, reintentando en 3 segundos..."
    sleep 3
done

echo "✅ Base de datos conectada"

# Ejecutar migraciones primero
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Limpiar y optimizar caché (después de migraciones)
echo "🧹 Optimizando aplicación..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar y crear archivo de historial si no existe
if [ ! -f /var/www/html/storage/app/predictions/history.csv ]; then
    echo "📝 Creando archivo de historial de predicciones..."
    echo "date,demand" > /var/www/html/storage/app/predictions/history.csv
fi

# Ajustar permisos finales
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

echo "✅ Aplicación lista"
echo "🌐 Iniciando Apache en puerto 8080..."

# Iniciar Apache
apache2-foreground