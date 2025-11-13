#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Tlaix..."

# Asegurarnos de que Apache esté escuchando en el puerto correcto
sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

# Iniciar Apache en segundo plano
apache2-foreground &
APACHE_PID=$!

# Función para verificar la conexión a la base de datos
check_db_connection() {
    php -r "
        \$host = getenv('DB_HOST');
        \$port = getenv('DB_PORT');
        \$timeout = 3;
        @fsockopen(\$host, \$port, \$errno, \$errstr, \$timeout);
    " > /dev/null 2>&1
}

# Esperar a que la base de datos esté disponible
echo "⏳ Verificando conexión a la base de datos..."
RETRIES=30
COUNT=0
until check_db_connection || [ $COUNT -eq $RETRIES ]; do
    echo "Intentando conectar a la base de datos... (intento $((COUNT+1))/$RETRIES)"
    COUNT=$((COUNT+1))
    sleep 2
done

if [ $COUNT -eq $RETRIES ]; then
    echo "⚠️ No se pudo establecer conexión con la base de datos después de $RETRIES intentos"
    echo "🔄 Continuando con el inicio de la aplicación..."
else
    echo "✅ Conexión a la base de datos establecida"
    
    # Ejecutar migraciones en segundo plano
# Intentar migraciones sin bloquear el inicio
(
    # Esperar un poco para que el sistema se estabilice
    sleep 5
    
    echo "�️ Eliminando datos anteriores y ejecutando migraciones frescas..."
    php artisan migrate:fresh --seed --force --no-interaction || echo "⚠️ Error en migraciones/seeders"
    
    echo "🧹 Optimizando la aplicación..."
        php artisan config:cache || true
        php artisan route:cache || true
        php artisan view:cache || true
    ) &
fi

# Verificar y ajustar permisos
echo "🔒 Ajustando permisos..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Asegurar que el directorio de logs exista con permisos correctos
mkdir -p /var/www/html/storage/logs
chmod -R 777 /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage/logs

# Mantener el script en ejecución y esperar a Apache
wait $APACHE_PID

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
    mkdir -p /var/www/html/storage/app/predictions
    echo "date,demand" > /var/www/html/storage/app/predictions/history.csv
else
    # Verificar si tiene contenido (más de una línea)
    LINE_COUNT=$(wc -l < /var/www/html/storage/app/predictions/history.csv)
    if [ "$LINE_COUNT" -le 1 ]; then
        echo "⚠️ history.csv existe pero está vacío, manteniendo estructura"
    else
        echo "✅ history.csv con datos históricos detectado ($LINE_COUNT líneas)"
    fi
fi

# Ajustar permisos finales
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage/logs

echo "✅ Aplicación lista"
echo "🌐 Iniciando Apache en puerto 8080..."

# Asegurarse de que Apache escuche en el puerto 8080
sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

# Iniciar Apache
apache2-foreground