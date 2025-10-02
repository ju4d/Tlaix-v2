@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
        <div class="text-3xl font-bold">{{ $totalIngredients }}</div>
        <div class="text-blue-100 mt-2">Insumos Totales</div>
    </div>
    <div class="text-white p-6 rounded-lg shadow-lg {{ $lowStock > 0 ? 'bg-red-500' : 'bg-green-500' }}">
        <div class="text-3xl font-bold">{{ $lowStock }}</div>
        <div class="mt-2 {{ $lowStock > 0 ? 'text-red-100' : 'text-green-100' }}">Insumos bajos</div>
    </div>
    <div class="bg-orange-500 text-white p-6 rounded-lg shadow-lg">
        <div class="text-3xl font-bold">{{ $totalDishes }}</div>
        <div class="text-orange-100 mt-2">Platillos Totales</div>
    </div>
    <div class="bg-purple-500 text-white p-6 rounded-lg shadow-lg">
        <div class="text-3xl font-bold">{{ $availableDishes }}</div>
        <div class="text-purple-100 mt-2">Platillos Disponibles</div>
    </div>
    <div class="bg-indigo-500 text-white p-6 rounded-lg shadow-lg">
        <div class="text-3xl font-bold">{{ $pendingOrders }}</div>
        <div class="text-indigo-100 mt-2">Pedidos Pendientes</div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <div class="flex items-center mb-6">
        <div class="bg-blue-100 p-3 rounded-full mr-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Acciones Rápidas</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('inventory.create') }}" class="group bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white p-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
            <div class="flex items-center">
                <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-sm">Agregar</div>
                    <div class="text-xs opacity-90">Nuevo Ingrediente</div>
                </div>
            </div>
        </a>

        <a href="{{ route('dishes.create') }}" class="group bg-gradient-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 text-white p-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
            <div class="flex items-center">
                <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-sm">Crear</div>
                    <div class="text-xs opacity-90">Nuevo Platillo</div>
                </div>
            </div>
        </a>

        <a href="{{ route('orders.index') }}" class="group bg-gradient-to-r from-purple-400 to-purple-600 hover:from-purple-500 hover:to-purple-700 text-white p-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
            <div class="flex items-center">
                <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m16 0l-2-2m-14 2l2-2"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-sm">Consultar</div>
                    <div class="text-xs opacity-90">Pedidos</div>
                </div>
            </div>
        </a>

        <a href="{{ route('reports') }}" class="group bg-gradient-to-r from-indigo-400 to-indigo-600 hover:from-indigo-500 hover:to-indigo-700 text-white p-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
            <div class="flex items-center">
                <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-sm">Generar</div>
                    <div class="text-xs opacity-90">Reportes</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Critical Stock Items - Full Width -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <div class="flex items-center mb-6">
        <div class="bg-red-100 p-3 rounded-full mr-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Stock Crítico</h3>
    </div>

    @if($lowStockItems->count() > 0)
        <div class="space-y-4">
            @foreach($lowStockItems as $item)
                <div class="border-l-4 {{ $item->stock < $item->min_stock ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50' }} p-6 rounded-r-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <h4 class="font-bold text-xl text-gray-900 mr-4">{{ $item->name }}</h4>
                            @if($item->category)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($item->category) }}
                                </span>
                            @endif
                        </div>

                        @if($item->stock < $item->min_stock)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                CRÍTICO
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                STOCK BAJO
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                        <div class="text-center">
                            <span class="text-sm text-gray-500 block mb-1">Stock Actual</span>
                            <span class="font-bold text-2xl {{ $item->stock < $item->min_stock ? 'text-red-600' : 'text-yellow-600' }}">
                                {{ $item->stock }}
                            </span>
                            <span class="text-sm text-gray-600">{{ $item->unit }}</span>
                        </div>

                        <div class="text-center">
                            <span class="text-sm text-gray-500 block mb-1">Stock Mínimo</span>
                            <span class="font-bold text-2xl text-gray-900">{{ $item->min_stock }}</span>
                            <span class="text-sm text-gray-600">{{ $item->unit }}</span>
                        </div>

                        <div class="md:col-span-1">
                            @php
                                $percentage = $item->min_stock > 0 ? min(($item->stock / $item->min_stock) * 100, 100) : 100;
                                $barColor = $item->stock < $item->min_stock ? 'bg-red-500' : 'bg-yellow-500';
                                $bgColor = $item->stock < $item->min_stock ? 'bg-red-100' : 'bg-yellow-100';
                            @endphp
                            <div class="text-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Nivel de Stock</span>
                                <div class="text-lg font-bold {{ $item->stock < $item->min_stock ? 'text-red-600' : 'text-yellow-600' }}">
                                    {{ number_format($percentage, 0) }}%
                                </div>
                            </div>
                            <div class="w-full {{ $bgColor }} rounded-full h-4">
                                <div class="{{ $barColor }} h-4 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <div class="bg-green-100 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">¡Excelente!</h4>
            <p class="text-gray-600">Todos los ingredientes tienen buen stock</p>
        </div>
    @endif
</div>

<!-- Real-Time Demand Statistics -->
<div class="card" style="margin-top: 30px; padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; margin: 0;">Estadísticas de Demanda en Tiempo Real</h3>
        <div style="display: flex; gap: 16px; align-items: center;">
            <button onclick="openRecordModal()" class="btn btn-success" style="background: #27ae60; padding: 12px 18px; font-size: 15px; border-radius: 8px;">
                Registrar Demanda
            </button>
            <button onclick="autoRecordDemand()" class="btn" style="background: #f39c12; padding: 12px 18px; font-size: 15px; border-radius: 8px;">
                Auto-registrar Hoy
            </button>
            <span class="status-indicator">
                <span class="status-dot"></span>
                Actualización automática
            </span>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Demand Stats Mini Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="mini-stat-card" style="border-left: 4px solid #3498db; padding: 20px;">
            <div class="mini-stat-label" style="margin-bottom: 8px;">Demanda Hoy</div>
            <div class="mini-stat-value" id="demandToday" style="margin-bottom: 8px;">-</div>
            <small>unidades</small>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #27ae60; padding: 20px;">
            <div class="mini-stat-label" style="margin-bottom: 8px;">Demanda Semanal</div>
            <div class="mini-stat-value" id="demandWeek" style="margin-bottom: 8px;">-</div>
            <small>unidades</small>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #f39c12; padding: 20px;">
            <div class="mini-stat-label" style="margin-bottom: 8px;">Demanda Mensual</div>
            <div class="mini-stat-value" id="demandMonth" style="margin-bottom: 8px;">-</div>
            <small>unidades</small>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #9b59b6; padding: 20px;">
            <div class="mini-stat-label" style="margin-bottom: 8px;">Promedio Diario</div>
            <div class="mini-stat-value" id="demandAvg" style="margin-bottom: 8px;">-</div>
            <small>unidades</small>
        </div>
    </div>
</div>

<!-- Ingredient Predictions -->
<div class="card" style="margin-top: 30px; padding: 24px;">
    <h3 style="font-size: 20px; margin-bottom: 20px;">🔮 Predicciones de Reabastecimiento por Ingrediente</h3>
    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 15px; flex-wrap: wrap;">
        <button onclick="loadIngredientPredictions(7)" class="btn btn-primary">7 días</button>
        <button onclick="loadIngredientPredictions(14)" class="btn">14 días</button>
        <button onclick="loadIngredientPredictions(30)" class="btn">30 días</button>
        <button onclick="loadSuggestedOrders()" class="btn" style="background: #27ae60; margin-left: auto;">
            Ver Órdenes Sugeridas
        </button>
        <span id="loadingIngredients" style="display: none; color: #3498db;">Calculando...</span>
    </div>

    <!-- Summary Cards -->
    <div id="predictionSummary" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 20px;"></div>

    <!-- Predictions Table -->
    <div id="ingredientPredictions"></div>
</div>

<!-- Recent Activity -->
<div class="card" style="margin-top: 20px;">
    <h3>Actividad Reciente y Pendientes</h3>
    <div id="recentActivity">
        @if($pendingOrders > 0)
            <div class="activity-item">
                <span class="activity-icon"></span>
                <span>{{ $pendingOrders }} Órdenes pendientes</span>
                <span class="activity-time">Ahora</span>
            </div>
        @endif

        @if($lowStock > 0)
            <div class="activity-item">
                <span class="activity-icon"></span>
                <span>{{ $lowStock }} ingredientes bajo el mínimo</span>
                <span class="activity-time">Ahora</span>
            </div>
        @endif

        <div class="activity-item">
            <span class="activity-icon"></span>
            <span>Dashboard cargado correctamente</span>
            <span class="activity-time">Justo ahora</span>
        </div>
    </div>
</div>

<!-- Record Demand Modal -->
<div id="recordModal" class="modal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Registrar Demanda</h2>
            <button onclick="closeRecordModal()" style="background: none; border: none; font-size: 1.5em; cursor: pointer; color: #7f8c8d;">×</button>
        </div>
        <form id="recordForm" onsubmit="recordDemand(event)">
            <div class="form-group">
                <label>Fecha:</label>
                <input type="date" id="demandDate" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="form-group">
                <label>Cantidad (unidades):</label>
                <input type="number" id="demandQuantity" min="0" step="0.1" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success" style="flex: 1; background: #27ae60; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer;">
                    Guardar
                </button>
                <button type="button" class="btn" onclick="closeRecordModal()" style="flex: 1; background: #95a5a6; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer;">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Suggested Orders Modal -->
<div id="ordersModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;"Órdenes de Compra Sugeridas</h2>
            <button onclick="closeOrdersModal()" style="background: none; border: none; font-size: 1.5em; cursor: pointer; color: #7f8c8d;">×</button>
        </div>
        <div id="suggestedOrdersContent"></div>
    </div>
</div>

<style>
/* Quick Action Buttons */
.quick-action-btn {
    display: block;
    padding: 12px 15px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    text-align: center;
    transition: all 0.2s ease;
}
.quick-action-btn:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Buttons */
.btn {
    padding: 8px 16px;
    background: #95a5a6;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-primary {
    background: #3498db;
}
.btn-success {
    background: #27ae60;
}
.btn:hover {
    opacity: 0.8;
    transform: translateY(-1px);
}

/* Activity Items */
.activity-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #ecf0f1;
    gap: 10px;
}
.activity-item:last-child {
    border-bottom: none;
}
.activity-icon {
    font-size: 1.2em;
}
.activity-time {
    margin-left: auto;
    color: #7f8c8d;
    font-size: 0.9em;
}

/* Status Indicator */
.status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9em;
    color: #7f8c8d;
}
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #27ae60;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Mini Stat Cards */
.mini-stat-card {
    background: white;
    padding: 15px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}
.mini-stat-label {
    color: #7f8c8d;
    font-size: 0.85em;
    margin-bottom: 8px;
}
.mini-stat-value {
    font-size: 1.8em;
    font-weight: bold;
    color: #2c3e50;
    margin: 5px 0;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s ease;
}
.modal-content {
    background: white;
    margin: 5% auto;
    padding: 30px;
    border-radius: 8px;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    animation: slideIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #2c3e50;
}

/* Alerts */
.alert {
    padding: 12px 20px;
    border-radius: 6px;
    margin-bottom: 15px;
    animation: slideInDown 0.3s ease;
}
.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}
.alert-error {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}
@keyframes slideInDown {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Prediction Table */
.prediction-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
.prediction-table th,
.prediction-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ecf0f1;
}
.prediction-table th {
    background: #f8f9fa;
    font-weight: 600;
}
.prediction-table tr:hover {
    background: #f8f9fa;
}

/* Urgency Badges */
.urgency-critical {
    background: #e74c3c;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
}
.urgency-high {
    background: #f39c12;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
}
.urgency-medium {
    background: #3498db;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
}
.urgency-low {
    background: #95a5a6;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
}
.urgency-normal {
    background: #27ae60;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
}

/* Supplier Card */
.supplier-card {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.supplier-card h4 {
    margin-top: 0;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

</style>

<script>
let autoRefreshInterval = null;
let currentPredictionDays = 7;

// Configurar fecha por defecto
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('demandDate').valueAsDate = new Date();

    // Cargar datos iniciales
    loadDemandSummary();

    // Cargar predicciones por ingrediente después de un breve delay
    setTimeout(() => {
        loadIngredientPredictions(7);
    }, 1000);

    // Actualización automática cada 30 segundos
    autoRefreshInterval = setInterval(() => {
        loadDemandSummary();
        console.log('Datos actualizados automáticamente');
    }, 30000);
});

// Modal Functions
function openRecordModal() {
    document.getElementById('recordModal').style.display = 'block';
}

function closeRecordModal() {
    document.getElementById('recordModal').style.display = 'none';
    document.getElementById('recordForm').reset();
    document.getElementById('demandDate').valueAsDate = new Date();
}

function closeOrdersModal() {
    document.getElementById('ordersModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const recordModal = document.getElementById('recordModal');
    const ordersModal = document.getElementById('ordersModal');
    if (event.target == recordModal) {
        closeRecordModal();
    }
    if (event.target == ordersModal) {
        closeOrdersModal();
    }
}

// Registrar demanda manualmente
async function recordDemand(event) {
    event.preventDefault();

    const date = document.getElementById('demandDate').value;
    const quantity = document.getElementById('demandQuantity').value;

    try {
        const response = await fetch('/api/demand/record', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ date, quantity: parseFloat(quantity) })
        });

        const data = await response.json();

        if (data.success) {
            showAlert('Demanda registrada correctamente', 'success');
            closeRecordModal();
            loadDemandSummary();
        } else {
            showAlert('Error: ' + (data.error || 'Error desconocido'), 'error');
        }
    } catch (error) {
        showAlert('Error de conexión: ' + error.message, 'error');
    }
}

// Auto-registrar demanda del día
async function autoRecordDemand() {
    try {
        showAlert('Registrando demanda automáticamente...', 'success');

        const response = await fetch('/api/demand/auto-record', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            if (data.recorded) {
                showAlert(`Demanda registrada: ${data.quantity} unidades`, 'success');
            } else {
                showAlert('No hay datos para registrar hoy', 'success');
            }
            loadDemandSummary();
        } else {
            showAlert('Error: ' + (data.error || 'No hay datos para registrar'), 'error');
        }
    } catch (error) {
        showAlert('Error: ' + error.message, 'error');
    }
}

// Cargar resumen de demanda
async function loadDemandSummary() {
    try {
        const response = await fetch('/api/demand/summary');
        const data = await response.json();

        document.getElementById('demandToday').textContent = data.today.toFixed(1);
        document.getElementById('demandWeek').textContent = data.week.toFixed(1);
        document.getElementById('demandMonth').textContent = data.month.toFixed(1);
        document.getElementById('demandAvg').textContent = data.average_daily.toFixed(1);

    } catch (error) {
        console.error('Error cargando resumen:', error);
    }
}

// Cargar predicciones de ingredientes
async function loadIngredientPredictions(days) {
    currentPredictionDays = days;
    const loading = document.getElementById('loadingIngredients');
    const container = document.getElementById('ingredientPredictions');

    loading.style.display = 'inline';
    container.innerHTML = '<div style="text-align: center; padding: 20px; color: #7f8c8d;">Analizando consumo y calculando predicciones...</div>';

    try {
        const response = await fetch(`/api/ingredient-predictions?days=${days}`);
        const data = await response.json();

        loading.style.display = 'none';

        if (data.success) {
            displayPredictionSummary(data.summary);
            displayIngredientPredictions(data.predictions, days);
        } else {
            container.innerHTML = `<p style="color: #e74c3c;">Error: ${data.error}</p>`;
        }
    } catch (error) {
        loading.style.display = 'none';
        console.error('Error:', error);
        container.innerHTML = '<p style="color: #e74c3c;">Error al cargar predicciones. Verifica que el controlador esté instalado.</p>';
    }
}

// Mostrar resumen de predicciones
function displayPredictionSummary(summary) {
    const container = document.getElementById('predictionSummary');

    const html = `
        <div class="mini-stat-card" style="border-left: 4px solid #3498db;">
            <div class="mini-stat-label">Total Ingredientes</div>
            <div class="mini-stat-value">${summary.total_ingredients}</div>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #e74c3c;">
            <div class="mini-stat-label">Necesitan Reabasto</div>
            <div class="mini-stat-value">${summary.needs_restock}</div>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #f39c12;">
            <div class="mini-stat-label">Alta Prioridad</div>
            <div class="mini-stat-value">${summary.top_priority_count}</div>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #9b59b6;">
            <div class="mini-stat-label">Costo Estimado</div>
            <div class="mini-stat-value">$${summary.estimated_total_cost.toFixed(2)}</div>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #27ae60;">
            <div class="mini-stat-label">Stock Normal</div>
            <div class="mini-stat-value">${summary.by_urgency.normal}</div>
        </div>
    `;

    container.innerHTML = html;
}

// Mostrar tabla de predicciones por ingrediente
function displayIngredientPredictions(predictions, days) {
    const container = document.getElementById('ingredientPredictions');

    // Filtrar y separar por urgencia
    const critical = predictions.filter(p => p.urgency === 'critical');
    const high = predictions.filter(p => p.urgency === 'high');
    const medium = predictions.filter(p => p.urgency === 'medium');
    const needsRestock = [...critical, ...high, ...medium];
    const others = predictions.filter(p => !needsRestock.includes(p));

    let html = '';

    // Sección de ingredientes que necesitan reabastecimiento
    if (needsRestock.length > 0) {
        html += `
            <div style="background: #fff3cd; border-left: 4px solid #f39c12; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                <h4 style="margin: 0 0 10px 0;"> ${needsRestock.length} Ingrediente(s) necesitan reabastecimiento en los próximos ${days} días</h4>
            </div>
        `;

        html += '<h4>Ingredientes Críticos y Prioritarios</h4>';
        html += '<table class="prediction-table">';
        html += `
            <thead>
                <tr>
                    <th>Ingrediente</th>
                    <th>Stock Actual</th>
                    <th>Consumo Proyectado</th>
                    <th>Stock Final</th>
                    <th>Días hasta Agotarse</th>
                    <th>Urgencia</th>
                    <th>Cantidad a Ordenar</th>
                    <th>Costo</th>
                    <th>Proveedor</th>
                </tr>
            </thead>
            <tbody>
        `;

        needsRestock.forEach(pred => {
            const urgencyClass = `urgency-${pred.urgency}`;
            const urgencyText = {
                'critical': 'CRÍTICO',
                'high': 'ALTO',
                'medium': 'MEDIO'
            }[pred.urgency];

            const rowBg = pred.urgency === 'critical' ? 'background: #ffe6e6;' :
                         pred.urgency === 'high' ? 'background: #fff3cd;' :
                         'background: #d1ecf1;';

            html += `
                <tr style="${rowBg}">
                    <td>
                        <strong>${pred.ingredient_name}</strong>
                        <br><small style="color: #666;">${pred.category || 'Sin categoría'}</small>
                        ${pred.dishes_using.length > 0 ? `<br><small style="color: #3498db;">Usado en: ${pred.dishes_using.slice(0, 2).join(', ')}${pred.dishes_using.length > 2 ? '...' : ''}</small>` : ''}
                    </td>
                    <td>
                        <strong>${pred.current_stock.toFixed(1)}</strong> ${pred.unit}
                        <br><small style="color: #666;">Mín: ${pred.min_stock.toFixed(1)}</small>
                    </td>
                    <td><strong style="color: #e74c3c;">${pred.projected_consumption.toFixed(1)}</strong> ${pred.unit}</td>
                    <td>
                        <strong style="color: ${pred.stock_after_period < 0 ? '#e74c3c' : pred.stock_after_period < pred.min_stock ? '#f39c12' : '#27ae60'};">
                            ${pred.stock_after_period.toFixed(1)}
                        </strong> ${pred.unit}
                    </td>
                    <td>
                        <strong style="color: ${pred.days_until_stockout < 3 ? '#e74c3c' : pred.days_until_stockout < 7 ? '#f39c12' : '#27ae60'};">
                            ${pred.days_until_stockout < 999 ? pred.days_until_stockout.toFixed(0) + ' días' : '∞'}
                        </strong>
                    </td>
                    <td><span class="${urgencyClass}">${urgencyText}</span></td>
                    <td><strong style="color: #27ae60;">${pred.recommended_order_quantity.toFixed(1)}</strong> ${pred.unit}</td>
                    <td><strong>$${pred.cost_estimate.toFixed(2)}</strong></td>
                    <td>
                        ${pred.supplier ? `
                            <strong>${pred.supplier.name}</strong>
                            ${pred.supplier.contact ? `<br><small>${pred.supplier.contact}</small>` : ''}
                        ` : '<span style="color: #e74c3c;">Sin proveedor</span>'}
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
    } else {
        html += `
            <div style="background: #d4edda; border-left: 4px solid #27ae60; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #27ae60;">Todos los ingredientes tienen stock suficiente para los próximos ${days} días</h4>
            </div>
        `;
    }

    // Sección de ingredientes con stock normal (colapsable)
    if (others.length > 0) {
        html += `
            <div style="margin-top: 30px;">
                <h4 style="cursor: pointer;" onclick="toggleOthers()">
                    Otros Ingredientes con Stock Normal (${others.length})
                    <span id="toggleIcon">▼</span>
                </h4>
                <div id="othersTable" style="display: none;">
                    <table class="prediction-table">
                        <thead>
                            <tr>
                                <th>Ingrediente</th>
                                <th>Stock Actual</th>
                                <th>Consumo Proyectado</th>
                                <th>Stock Final</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        others.forEach(pred => {
            html += `
                <tr>
                    <td><strong>${pred.ingredient_name}</strong></td>
                    <td>${pred.current_stock.toFixed(1)} ${pred.unit}</td>
                    <td>${pred.projected_consumption.toFixed(1)} ${pred.unit}</td>
                    <td><strong style="color: #27ae60;">${pred.stock_after_period.toFixed(1)}</strong> ${pred.unit}</td>
                    <td><span class="urgency-normal">OK</span></td>
                </tr>
            `;
        });

        html += '</tbody></table></div></div>';
    }

    container.innerHTML = html;
}

// Toggle para mostrar/ocultar ingredientes con stock normal
function toggleOthers() {
    const table = document.getElementById('othersTable');
    const icon = document.getElementById('toggleIcon');

    if (table.style.display === 'none') {
        table.style.display = 'block';
        icon.textContent = '▲';
    } else {
        table.style.display = 'none';
        icon.textContent = '▼';
    }
}

// Cargar órdenes sugeridas
async function loadSuggestedOrders() {
    try {
        const response = await fetch(`/api/ingredient-predictions/suggested-orders?days=${currentPredictionDays}`);
        const data = await response.json();

        if (data.success) {
            displaySuggestedOrders(data);
            document.getElementById('ordersModal').style.display = 'block';
        } else {
            showAlert('Error al generar órdenes: ' + data.error, 'error');
        }
    } catch (error) {
        showAlert('Error de conexión: ' + error.message, 'error');
    }
}

// Mostrar órdenes sugeridas agrupadas por proveedor
function displaySuggestedOrders(data) {
    const container = document.getElementById('suggestedOrdersContent');

    if (data.total_items === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #27ae60;">
                <div style="font-size: 3em; margin-bottom: 20px;"></div>
                <h3>No hay ingredientes que necesiten reabastecimiento</h3>
                <p>Todos los ingredientes tienen stock suficiente.</p>
            </div>
        `;
        return;
    }

    let html = `
        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                <div>
                    <strong>Total de Proveedores:</strong>
                    <div style="font-size: 1.5em; color: #3498db;">${data.total_suppliers}</div>
                </div>
                <div>
                    <strong>Total de Productos:</strong>
                    <div style="font-size: 1.5em; color: #f39c12;">${data.total_items}</div>
                </div>
                <div>
                    <strong>Costo Total Estimado:</strong>
                    <div style="font-size: 1.5em; color: #27ae60;">${data.grand_total_cost.toFixed(2)}</div>
                </div>
            </div>
        </div>
    `;

    data.suggested_orders.forEach((order, index) => {
        const supplierIcon = order.supplier_id ? '' : '';
        const borderColor = order.supplier_id ? '#3498db' : '#e74c3c';

        html += `
            <div class="supplier-card" style="border-color: ${borderColor};">
                <h4>
                    ${supplierIcon} ${order.supplier_name}
                    <span style="margin-left: auto; font-size: 0.9em; color: #27ae60;">
                        Total: ${order.total_cost.toFixed(2)}
                    </span>
                </h4>
                ${order.supplier_contact ? `<p style="color: #7f8c8d; margin: 5px 0;">Contacto: ${order.supplier_contact}</p>` : ''}

                <div class="supplier-items">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 8px; text-align: left;">Ingrediente</th>
                                <th style="padding: 8px; text-align: center;">Cantidad</th>
                                <th style="padding: 8px; text-align: center;">Urgencia</th>
                                <th style="padding: 8px; text-align: right;">Costo</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        order.items.forEach(item => {
            const urgencyClass = `urgency-${item.urgency}`;
            const urgencyEmoji = {
                'critical': '',
                'high': '',
                'medium': '',
                'low': '',
                'normal': ''
            }[item.urgency];

            html += `
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 8px;">
                        <strong>${item.ingredient_name}</strong>
                        <br><small style="color: #666;">${item.category || ''}</small>
                    </td>
                    <td style="padding: 8px; text-align: center;">
                        <strong>${item.recommended_order_quantity.toFixed(1)}</strong> ${item.unit}
                    </td>
                    <td style="padding: 8px; text-align: center;">
                        <span class="${urgencyClass}">${urgencyEmoji} ${item.urgency.toUpperCase()}</span>
                    </td>
                    <td style="padding: 8px; text-align: right;">
                        <strong>${item.cost_estimate.toFixed(2)}</strong>
                    </td>
                </tr>
            `;
        });

        html += `
                        </tbody>
                    </table>
                </div>

                ${order.supplier_id ? `
                    <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
                        <button onclick="autoCreateOrder(${order.supplier_id})"
                                class="btn btn-success"
                                style="background: #27ae60; padding: 10px 20px;">
                            Crear Pedido Automático
                        </button>
                        <a href="/orders/create?supplier_id=${order.supplier_id}"
                           class="btn"
                           style="background: #3498db; padding: 10px 20px; color: white; text-decoration: none; display: inline-block; border-radius: 4px;">
                            Crear Manualmente
                        </a>
                    </div>
                ` : `
                    <div style="margin-top: 15px; padding: 10px; background: #fff3cd; border-radius: 4px;">
                        <small style="color: #856404;">
                            Asigna un proveedor a estos ingredientes para poder crear el pedido automáticamente.
                        </small>
                    </div>
                `}
            </div>
        `;
    });

    html += `
        <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="exportOrdersToCSV()" class="btn" style="background: #3498db;">
                Exportar CSV
            </button>
            <button onclick="window.print()" class="btn" style="background: #95a5a6;">
                Imprimir
            </button>
        </div>
    `;

    container.innerHTML = html;
}

// Exportar órdenes a CSV
function exportOrdersToCSV() {
    fetch(`/api/ingredient-predictions/suggested-orders?days=${currentPredictionDays}`)
        .then(response => response.json())
        .then(data => {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Proveedor,Ingrediente,Categoría,Cantidad,Unidad,Urgencia,Costo Estimado\n";

            data.suggested_orders.forEach(order => {
                order.items.forEach(item => {
                    const row = [
                        order.supplier_name,
                        item.ingredient_name,
                        item.category || '',
                        item.recommended_order_quantity.toFixed(2),
                        item.unit,
                        item.urgency,
                        item.cost_estimate.toFixed(2)
                    ].join(',');
                    csvContent += row + "\n";
                });
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `ordenes_sugeridas_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showAlert('CSV exportado correctamente', 'success');
        })
        .catch(error => {
            showAlert('Error al exportar: ' + error.message, 'error');
        });
}

// Mostrar alertas
function showAlert(message, type) {
    const container = document.getElementById('alertContainer');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;

    container.appendChild(alert);

    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    }, 4000);
}

// Limpiar intervalo al salir
window.addEventListener('beforeunload', () => {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
});

// Crear pedido automáticamente
async function autoCreateOrder(supplierId) {
    if (!confirm('¿Crear pedido automático con las cantidades recomendadas?\n\nEsto creará un pedido en estado PENDIENTE que podrás revisar antes de confirmarlo.')) {
        return;
    }

    showAlert('Creando pedido automáticamente...', 'success');

    try {
        const response = await fetch('/api/ingredient-predictions/auto-create-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                supplier_id: supplierId,
                days: currentPredictionDays
            })
        });

        const data = await response.json();

        if (data.success) {
            showAlert(`Pedido #${data.order_id} creado exitosamente con ${data.items_count} productos. Total: ${data.total.toFixed(2)}`, 'success');

            // Cerrar modal y redirigir después de 2 segundos
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 2000);
        } else {
            showAlert('Error: ' + data.error, 'error');
        }
    } catch (error) {
        showAlert('Error de conexión: ' + error.message, 'error');
    }
}
</script>
@endsection
