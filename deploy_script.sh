#!/bin/bash

# Script de despliegue para IBM Cloud
# Asegúrate de tener instalado IBM Cloud CLI

echo "🚀 Iniciando despliegue a IBM Cloud..."

# 1. Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: No se encuentra el archivo artisan. Ejecuta este script desde la raíz del proyecto Laravel."
    exit 1
fi

# 2. Generar APP_KEY si no existe
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate
fi

# 3. Limpiar caché
echo "🧹 Limpiando caché..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Optimizar para producción
echo "⚡ Optimizando aplicación..."
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache

# 5. Crear directorios necesarios
echo "📁 Creando directorios..."
mkdir -p storage/app/predictions
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# 6. Establecer permisos
echo "🔒 Configurando permisos..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 7. Login a IBM Cloud
echo "🔐 Iniciando sesión en IBM Cloud..."
ibmcloud login

# 8. Seleccionar organización y espacio
echo "📍 Seleccionando organización y espacio..."
ibmcloud target --cf

# 9. Crear servicio de base de datos (si no existe)
echo "🗄️  Configurando base de datos..."
read -p "¿Deseas crear un servicio de base de datos? (s/n): " crear_db
if [ "$crear_db" = "s" ]; then
    echo "Servicios disponibles:"
    ibmcloud cf marketplace | grep -i sql
    read -p "Nombre del servicio (ej: cleardb): " db_service
    read -p "Plan (ej: spark): " db_plan
    read -p "Nombre de instancia (ej: tlaix-db): " db_instance

    ibmcloud cf create-service "$db_service" "$db_plan" "$db_instance"

    # Actualizar manifest.yml con el servicio
    echo "  services:" >> manifest.yml
    echo "    - $db_instance" >> manifest.yml
fi

# 10. Desplegar aplicación
echo "🚀 Desplegando aplicación..."
ibmcloud cf push

# 11. Ejecutar migraciones
echo "🗃️  ¿Deseas ejecutar migraciones? (s/n): "
read ejecutar_migraciones
if [ "$ejecutar_migraciones" = "s" ]; then
    ibmcloud cf run-task tlaix-app "php artisan migrate --force"
fi

# 12. Verificar el despliegue
echo "✅ Despliegue completado!"
echo ""
echo "📊 Estado de la aplicación:"
ibmcloud cf app tlaix-app

echo ""
echo "🌐 URL de la aplicación:"
ibmcloud cf app tlaix-app | grep -i "routes:"

echo ""
echo "📝 Para ver los logs ejecuta: ibmcloud cf logs tlaix-app --recent"
