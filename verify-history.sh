#!/bin/bash
# Script para verificar que history.csv se haya copiado correctamente

echo "🔍 Verificando history.csv en el contenedor..."

HISTORY_FILE="/var/www/html/storage/app/predictions/history.csv"

if [ -f "$HISTORY_FILE" ]; then
    LINE_COUNT=$(wc -l < "$HISTORY_FILE")
    echo "✅ Archivo encontrado: $HISTORY_FILE"
    echo "📊 Número de líneas: $LINE_COUNT"
    
    if [ "$LINE_COUNT" -gt 10 ]; then
        echo "✅ El archivo contiene datos históricos"
        echo "📋 Primeras 5 líneas:"
        head -5 "$HISTORY_FILE"
        echo "..."
        echo "📋 Últimas 5 líneas:"
        tail -5 "$HISTORY_FILE"
    else
        echo "⚠️ El archivo existe pero tiene pocos datos ($LINE_COUNT líneas)"
        cat "$HISTORY_FILE"
    fi
else
    echo "❌ ERROR: history.csv NO encontrado en $HISTORY_FILE"
    echo "📂 Contenido de storage/app/predictions/:"
    ls -la /var/www/html/storage/app/predictions/ || echo "El directorio no existe"
fi
