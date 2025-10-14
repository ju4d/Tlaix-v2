#!/bin/bash

# Colores para mensajes
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}🚀 Iniciando despliegue a IBM Cloud Code Engine...${NC}"

# 1. Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: No se encuentra el archivo artisan. Ejecuta este script desde la raíz del proyecto Laravel.${NC}"
    exit 1
fi

# 2. Verificar cambios de git pendientes
echo -e "\n${BLUE}📊 Verificando estado de git...${NC}"
if [[ -n $(git status -s) ]]; then
    echo -e "${RED}⚠️  Tienes cambios pendientes. ¿Deseas hacer commit de estos cambios? (s/n): ${NC}"
    read hacer_commit
    if [ "$hacer_commit" = "s" ]; then
        echo -e "${BLUE}📝 Ingresa el mensaje para el commit:${NC}"
        read commit_message
        git add .
        git commit -m "$commit_message"
        git push origin main
    else
        echo -e "${RED}❌ Por favor, maneja tus cambios pendientes antes de desplegar.${NC}"
        exit 1
    fi
fi

# 3. Limpiar caché y optimizar
echo -e "\n${BLUE}🧹 Preparando la aplicación...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache

# 4. Construir imagen Docker
echo -e "\n${BLUE}🏗️  Construyendo imagen Docker...${NC}"
if ! docker build -t us.icr.io/tlaix-images/tlaix-app:v2 .; then
    echo -e "${RED}❌ Error al construir la imagen Docker${NC}"
    exit 1
fi

# 5. Subir imagen a IBM Cloud Registry
echo -e "\n${BLUE}⬆️  Subiendo imagen a IBM Cloud Registry...${NC}"
if ! docker push us.icr.io/tlaix-images/tlaix-app:v2; then
    echo -e "${RED}❌ Error al subir la imagen a IBM Cloud Registry${NC}"
    exit 1
fi

# 6. Actualizar aplicación en IBM Cloud Code Engine
echo -e "\n${BLUE}🔄 Actualizando aplicación en IBM Cloud...${NC}"
if ! ibmcloud ce app update --name tlaix-app --image us.icr.io/tlaix-images/tlaix-app:v2; then
    echo -e "${RED}❌ Error al actualizar la aplicación en IBM Cloud${NC}"
    exit 1
fi

# 7. Verificar estado de la aplicación
echo -e "\n${BLUE}🔍 Verificando estado de la aplicación...${NC}"
ibmcloud ce app get --name tlaix-app

# 8. Preguntar si se desean ejecutar las migraciones
echo -e "\n${BLUE}🗃️  ¿Deseas ejecutar las migraciones de la base de datos? (s/n): ${NC}"
read ejecutar_migraciones
if [ "$ejecutar_migraciones" = "s" ]; then
    echo -e "\n${BLUE}📦 Ejecutando migraciones...${NC}"
    php artisan migrate --force
fi

# 9. Preguntar si se desean ejecutar los seeders
echo -e "\n${BLUE}🌱 ¿Deseas ejecutar los seeders? (s/n): ${NC}"
read ejecutar_seeders
if [ "$ejecutar_seeders" = "s" ]; then
    echo -e "${BLUE}¿Qué seeders deseas ejecutar?${NC}"
    echo "1. TlaixSeeder (datos base: usuarios, platillos, ingredientes)"
    echo "2. ConsumptionLogSeeder (datos de consumo)"
    echo "3. Ambos"
    read -p "Selecciona una opción (1-3): " opcion_seeder
    
    case $opcion_seeder in
        1)
            php artisan db:seed --class=TlaixSeeder
            ;;
        2)
            php artisan db:seed --class=ConsumptionLogSeeder
            ;;
        3)
            php artisan db:seed
            ;;
        *)
            echo -e "${RED}Opción no válida${NC}"
            ;;
    esac
fi

echo -e "\n${GREEN}✅ ¡Despliegue completado exitosamente!${NC}"
echo -e "${BLUE}📱 La aplicación está disponible en:${NC}"
echo -e "${GREEN}https://tlaix-app.21h73z23cnra.us-south.codeengine.appdomain.cloud${NC}"
echo -e "\n${BLUE}📝 Para ver los logs ejecuta:${NC}"
echo -e "ibmcloud ce application logs --name tlaix-app"
