# Tlaix - simple implementation

## Requisitos
- PHP 8.x, Composer
- MySQL
- Python 3.8+
- pip packages: pandas, numpy, scikit-learn

## Instalación básica (desarrollo)
1. Clona el repo
2. Copia `.env.example` a `.env` y configura DB (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
3. Instala dependencias:
   composer install
4. Crea la base de datos en MySQL
5. Ejecuta migraciones:
   php artisan migrate
6. Crea un usuario admin (opcional con tinker):
   php artisan tinker
   >>> \App\Models\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>\Illuminate\Support\Facades\Hash::make('secret'),'role'=>'admin']);

7. Prepara carpeta de predicciones:
   mkdir -p storage/app/predictions
   (coloca `history.csv` si tienes datos)

8. Instala Python deps:
   pip install pandas numpy scikit-learn

## Uso del servicio de predicción
Desde Laravel (o terminal):
`python3 predict.py --file storage/app/predictions/history.csv --days 7`

El script imprimirá JSON con predicciones:
`{"predictions":[{"date":"2025-09-01","demand":15.2}, ...]}`

Laravel endpoint para predicción:
`POST /predict` con `days` (opcional). Retorna JSON.

## Notas de diseño
- Autenticación simple vía `AuthController` (session-based). En producción reemplazar por Laravel auth scaffold.
- Cuando un ingrediente queda por debajo de `min_stock`, el controlador de inventario genera una orden automática (ejemplo).
- Plato se marca como no disponible si algún ingrediente tiene stock insuficiente.
- Reportes simples con Chart.js (CDN).

## Cómo mejorar / siguientes pasos
- Añadir middleware de autenticación/roles real.
- Validaciones, tests, y jobs para ejecución programada (scheduling) de predicciones.
- Integración con proveedor real para órdenes (API / email).
