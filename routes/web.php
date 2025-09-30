<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    InventoryController,
    DishController,
    OrderController,
    ReportController,
    PredictionController,
    SupplierController,
    ApiController,
    DemandController,
    IngredientPredictionController
};

// Rutas públicas
Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de inventario
    Route::resource('inventory', InventoryController::class);

    // Rutas de platos
    Route::resource('dishes', DishController::class);

    // Rutas de órdenes (completas)
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{id}/receive', [OrderController::class, 'receive'])->name('orders.receive');
    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Rutas de proveedores
    Route::resource('suppliers', SupplierController::class);

    // Reportes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // APIs de predicciones
    Route::prefix('api')->group(function () {
        // Predicciones generales
        Route::get('/predictions/{days}', [PredictionController::class, 'predict']);

        // Predicciones por ingrediente
        Route::get('/ingredient-predictions', [IngredientPredictionController::class, 'predictIngredientNeeds']);
        Route::get('/ingredient-predictions/suggested-orders', [IngredientPredictionController::class, 'generateSuggestedOrders']);

        // Reportes
        Route::get('/waste-report', [ApiController::class, 'wasteReport']);
        Route::get('/dashboard-stats', [ApiController::class, 'dashboardStats']);
        Route::get('/inventory-status', [ApiController::class, 'inventoryStatus']);
        Route::put('/inventory/{id}/stock', [ApiController::class, 'updateStock']);

        // Demanda en tiempo real
        Route::post('/demand/record', [DemandController::class, 'recordDemand']);
        Route::get('/demand/summary', [DemandController::class, 'getDemandSummary']);
        Route::post('/demand/auto-record', [DemandController::class, 'autoRecordDailyDemand']);
        Route::get('/demand/export', [DemandController::class, 'exportHistory']);
        Route::post('/demand/clean', [DemandController::class, 'cleanOldData']);
    });
});
