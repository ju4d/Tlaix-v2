<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function(){ return redirect('/login'); });

Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/logout', [AuthController::class,'logout'])->name('logout');

// Protected routes -- very simple middleware simulation
Route::middleware(['web'])->group(function(){
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    Route::resource('inventory', InventoryController::class)->only(['index','create','store','edit','update','destroy']);
    Route::resource('dishes', DishController::class);
    Route::resource('orders', OrderController::class)->only(['index','show','store']);
    Route::get('/reports', [ReportController::class,'index'])->name('reports');

    // Prediction endpoint — calls python script
    Route::post('/predict', [PredictionController::class,'predict'])->name('predict');
});
