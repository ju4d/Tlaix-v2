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

    // Rutas de órdenes
    Route::resource('orders', OrderController::class);

    // Rutas de proveedores
    Route::resource('suppliers', SupplierController::class);

    // Reportes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});
?>
<!-- filepath: c:\xampp\htdocs\tlaix\resources\views\home.blade.php -->
@extends('layouts.app')
@section('title','Inicio')
@section('content')
    <h1>Bienvenido, has iniciado sesión correctamente.</h1>
@endsection
