#!/bin/bash
set -e

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Script de Despliegue Tlaix a Digital Ocean${NC}"
echo ""

# Verificar que history.csv existe y tiene datos
echo -e "${YELLOW}📋 Verificando archivo history.csv...${NC}"
HISTORY_FILE="storage/app/predictions/history.csv"

if [ ! -f "$HISTORY_FILE" ]; then
    echo -e "${RED}❌ ERROR: $HISTORY_FILE no encontrado${NC}"
    echo -e "${YELLOW}Por favor, asegúrate de que el archivo existe antes de continuar${NC}"
    exit 1
fi

LINE_COUNT=$(wc -l < "$HISTORY_FILE")
echo -e "${GREEN}✅ Archivo encontrado: $LINE_COUNT líneas${NC}"

if [ "$LINE_COUNT" -le 1 ]; then
    echo -e "${YELLOW}⚠️  ADVERTENCIA: El archivo parece estar vacío (solo headers)${NC}"
    read -p "¿Deseas continuar de todos modos? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
else
    echo -e "${GREEN}✅ El archivo contiene $LINE_COUNT registros${NC}"
fi

# Verificar .dockerignore
echo ""
echo -e "${YELLOW}🔍 Verificando .dockerignore...${NC}"
if grep -q "!storage/app/predictions/history.csv" .dockerignore; then
    echo -e "${GREEN}✅ .dockerignore configurado correctamente${NC}"
else
    echo -e "${RED}❌ ERROR: .dockerignore no tiene la excepción para history.csv${NC}"
    echo -e "${YELLOW}Agregando excepción...${NC}"
    echo "!storage/app/predictions/history.csv" >> .dockerignore
    echo -e "${GREEN}✅ Excepción agregada${NC}"
fi

# Configuración
REGISTRY_NAME="${REGISTRY_NAME:-tlaix-registry}"
IMAGE_NAME="${IMAGE_NAME:-tlaix-app}"
VERSION="${VERSION:-latest}"

echo ""
echo -e "${BLUE}📦 Configuración:${NC}"
echo -e "  Registry: ${GREEN}$REGISTRY_NAME${NC}"
echo -e "  Imagen: ${GREEN}$IMAGE_NAME${NC}"
echo -e "  Versión: ${GREEN}$VERSION${NC}"
echo ""

# Paso 1: Construir imagen
echo -e "${YELLOW}🔨 Paso 1: Construyendo imagen Docker...${NC}"
if docker build -t "$IMAGE_NAME:$VERSION" . 2>&1 | tee /tmp/docker-build.log; then
    echo -e "${GREEN}✅ Imagen construida exitosamente${NC}"
    
    # Verificar que history.csv está en la imagen
    echo -e "${YELLOW}🔍 Verificando history.csv en la imagen...${NC}"
    if grep -q "Usando history.csv con datos históricos" /tmp/docker-build.log || \
       docker run --rm "$IMAGE_NAME:$VERSION" test -f /var/www/html/storage/app/predictions/history.csv; then
        
        CONTAINER_LINES=$(docker run --rm "$IMAGE_NAME:$VERSION" wc -l < /var/www/html/storage/app/predictions/history.csv 2>/dev/null || echo "0")
        if [ "$CONTAINER_LINES" -gt 1 ]; then
            echo -e "${GREEN}✅ history.csv incluido en la imagen ($CONTAINER_LINES líneas)${NC}"
        else
            echo -e "${RED}❌ WARNING: history.csv está en la imagen pero parece vacío${NC}"
        fi
    else
        echo -e "${RED}❌ ERROR: history.csv NO está en la imagen${NC}"
        exit 1
    fi
else
    echo -e "${RED}❌ Error construyendo imagen${NC}"
    exit 1
fi

# Paso 2: Etiquetar para Digital Ocean
echo ""
echo -e "${YELLOW}🏷️  Paso 2: Etiquetando imagen para Digital Ocean...${NC}"
FULL_IMAGE_NAME="registry.digitalocean.com/$REGISTRY_NAME/$IMAGE_NAME:$VERSION"
docker tag "$IMAGE_NAME:$VERSION" "$FULL_IMAGE_NAME"
echo -e "${GREEN}✅ Imagen etiquetada: $FULL_IMAGE_NAME${NC}"

# Paso 3: Login a Digital Ocean Registry
echo ""
echo -e "${YELLOW}🔐 Paso 3: Login a Digital Ocean Container Registry...${NC}"
if command -v doctl &> /dev/null; then
    if doctl registry login; then
        echo -e "${GREEN}✅ Login exitoso${NC}"
    else
        echo -e "${RED}❌ Error en login${NC}"
        echo -e "${YELLOW}Por favor, ejecuta 'doctl auth init' primero${NC}"
        exit 1
    fi
else
    echo -e "${RED}❌ ERROR: doctl no está instalado${NC}"
    echo -e "${YELLOW}Instala doctl: https://docs.digitalocean.com/reference/doctl/how-to/install/${NC}"
    exit 1
fi

# Paso 4: Push de la imagen
echo ""
echo -e "${YELLOW}📤 Paso 4: Subiendo imagen a Digital Ocean...${NC}"
if docker push "$FULL_IMAGE_NAME"; then
    echo -e "${GREEN}✅ Imagen subida exitosamente${NC}"
else
    echo -e "${RED}❌ Error subiendo imagen${NC}"
    exit 1
fi

# Paso 5: Información final
echo ""
echo -e "${GREEN}✅ ¡Despliegue completado exitosamente!${NC}"
echo ""
echo -e "${BLUE}📋 Próximos pasos:${NC}"
echo -e "  1. Ve a Digital Ocean App Platform"
echo -e "  2. Crea una nueva app o actualiza una existente"
echo -e "  3. Selecciona imagen: ${GREEN}$FULL_IMAGE_NAME${NC}"
echo -e "  4. Configura las variables de entorno (ver DOCKER_DEPLOY.md)"
echo -e "  5. Despliega la aplicación"
echo ""
echo -e "${YELLOW}💡 Comandos útiles:${NC}"
echo -e "  - Verificar imagen: ${GREEN}docker run --rm $IMAGE_NAME:$VERSION bash /var/www/html/verify-history.sh${NC}"
echo -e "  - Ver logs locales: ${GREEN}docker run --rm $IMAGE_NAME:$VERSION cat /var/www/html/storage/app/predictions/history.csv${NC}"
echo -e "  - Crear app: ${GREEN}doctl apps create --spec app-spec.yaml${NC}"
echo ""
echo -e "${BLUE}📖 Para más información, consulta DOCKER_DEPLOY.md${NC}"
