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
    ApiController
};

Route::get('/', function(){ return redirect('/login'); });

// Auth routes
Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/logout', [AuthController::class,'logout'])->name('logout');

// Protected routes
Route::middleware(['web'])->group(function(){
    // Check if user is logged in for all protected routes
    Route::middleware(['auth.simple'])->group(function(){
        Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

        // Inventory management
        Route::resource('inventory', InventoryController::class)->only([
            'index','create','store','edit','update','destroy'
        ]);

        // Dish management
        Route::resource('dishes', DishController::class);

        // Order management
        Route::resource('orders', OrderController::class)->only(['index','show','store']);
        Route::post('/orders/{id}/receive', [OrderController::class,'receive'])->name('orders.receive');

        // Supplier management
        Route::resource('suppliers', SupplierController::class);

        // Reports
        Route::get('/reports', [ReportController::class,'index'])->name('reports');

        // Prediction endpoint
        Route::post('/predict', [PredictionController::class,'predict'])->name('predict');

        // API endpoints for AJAX calls
        Route::prefix('api')->group(function() {
            Route::get('/predictions/{days?}', function($days = 7) {
                $controller = new PredictionController();
                $request = new \Illuminate\Http\Request(['days' => $days]);
                return $controller->predict($request);
            })->name('api.predictions');

            Route::get('/waste-report', [ApiController::class, 'wasteReport'])->name('api.waste');
            Route::get('/dashboard-stats', [ApiController::class, 'dashboardStats'])->name('api.dashboard');
            Route::get('/inventory-status', [ApiController::class, 'inventoryStatus'])->name('api.inventory');
            Route::put('/inventory/{id}/stock', [ApiController::class, 'updateStock'])->name('api.update-stock');
        });
    });
});
