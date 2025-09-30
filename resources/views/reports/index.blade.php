@extends('layouts.app')
@section('title','Reportes')
@section('content')

<!-- Header Section -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Panel de Reportes</h2>
            <p class="text-gray-600">Análisis completo del inventario y operaciones</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportToPDF()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                PDF
            </button>
            <button onclick="exportToCSV()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                CSV
            </button>
            <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimir
            </button>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium uppercase tracking-wide">Valor Total</p>
                <p class="text-3xl font-bold">${{ number_format($totalStockValue, 2) }}</p>
                <p class="text-green-100 text-sm">Inventario</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r {{ count($expired) > 0 ? 'from-red-500 to-red-600' : 'from-gray-400 to-gray-500' }} text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="{{ count($expired) > 0 ? 'text-red-100' : 'text-gray-100' }} text-sm font-medium uppercase tracking-wide">Caducados</p>
                <p class="text-3xl font-bold">{{ count($expired) }}</p>
                <p class="{{ count($expired) > 0 ? 'text-red-100' : 'text-gray-100' }} text-sm">Productos</p>
            </div>
            <div class="{{ count($expired) > 0 ? 'bg-red-400' : 'bg-gray-300' }} bg-opacity-30 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r {{ count($lowStock) > 0 ? 'from-orange-500 to-orange-600' : 'from-gray-400 to-gray-500' }} text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="{{ count($lowStock) > 0 ? 'text-orange-100' : 'text-gray-100' }} text-sm font-medium uppercase tracking-wide">Stock Bajo</p>
                <p class="text-3xl font-bold">{{ count($lowStock) }}</p>
                <p class="{{ count($lowStock) > 0 ? 'text-orange-100' : 'text-gray-100' }} text-sm">Productos</p>
            </div>
            <div class="{{ count($lowStock) > 0 ? 'bg-orange-400' : 'bg-gray-300' }} bg-opacity-30 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Disponibles</p>
                <p class="text-3xl font-bold">{{ $dishAnalysis['available'] }}/{{ $dishAnalysis['total'] }}</p>
                <p class="text-purple-100 text-sm">Platillos</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM6 6v12h8V6H6z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
    <!-- Inventory Levels Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Niveles de Inventario</h3>
            <div class="flex items-center space-x-4 text-sm">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                    <span class="text-gray-600">Debajo del mínimo</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-gray-600">Stock normal</span>
                </div>
            </div>
        </div>
        <div class="relative">
            <canvas id="inventoryChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Category Distribution -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Inventario por Categoría</h3>
            <div class="text-sm text-gray-500">Distribución de productos</div>
        </div>
        <div class="relative mb-4">
            <canvas id="categoryChart" class="w-full h-64"></canvas>
        </div>
        <div class="space-y-3">
            @foreach($categoryAnalysis as $category => $data)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-3" style="background: {{ ['#3498db', '#e74c3c', '#f39c12', '#27ae60', '#9b59b6'][array_search($category, array_keys($categoryAnalysis->toArray()))] ?? '#95a5a6' }}"></div>
                    <span class="font-medium text-gray-900">{{ ucfirst($category) }}</span>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-gray-900">{{ $data['count'] }} productos</div>
                    <div class="text-xs text-gray-500">{{ number_format($data['total_stock'], 1) }} unidades</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Critical Alerts -->
@if(count($expired) > 0 || count($expiringSoon) > 0 || count($lowStock) > 0)
<div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-l-4 border-red-500">
    <div class="flex items-center mb-4">
        <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900">Alertas Críticas</h3>
    </div>

    <div class="space-y-4">
        @if(count($expired) > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <h4 class="font-semibold text-red-800">{{ count($expired) }} Productos Caducados</h4>
            </div>
            <div class="space-y-2">
                @foreach($expired as $item)
                <div class="flex items-center justify-between p-3 bg-white rounded border border-red-200">
                    <div>
                        <span class="font-medium text-gray-900">{{ $item->name }}</span>
                        <span class="text-sm text-gray-500 ml-2">Caducó: {{ $item->expiration_date }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">{{ $item->stock }} {{ $item->unit }}</div>
                        <div class="text-sm text-red-600">${{ number_format($item->stock * ($item->cost ?? 0), 2) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(count($expiringSoon) > 0)
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4 class="font-semibold text-orange-800">{{ count($expiringSoon) }} Expiran en 3 días</h4>
            </div>
            <div class="space-y-2">
                @foreach($expiringSoon as $item)
                <div class="flex items-center justify-between p-3 bg-white rounded border border-orange-200">
                    <div>
                        <span class="font-medium text-gray-900">{{ $item->name }}</span>
                        <span class="text-sm text-gray-500 ml-2">Caduca: {{ $item->expiration_date }}</span>
                    </div>
                    <div class="text-sm font-medium text-gray-900">{{ $item->stock }} {{ $item->unit }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(count($lowStock) > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
                <h4 class="font-semibold text-yellow-800">{{ count($lowStock) }} Stock Bajo</h4>
            </div>
            <div class="space-y-2">
                @foreach($lowStock as $item)
                <div class="flex items-center justify-between p-3 bg-white rounded border border-yellow-200">
                    <div>
                        <span class="font-medium text-gray-900">{{ $item->name }}</span>
                        @if($item->supplier)
                            <span class="text-sm text-gray-500 ml-2">{{ $item->supplier->name }}</span>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-yellow-700">{{ $item->stock }}/{{ $item->min_stock }} {{ $item->unit }}</div>
                        <div class="text-xs text-gray-500">Reorden necesario</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@else
<div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-l-4 border-green-500">
    <div class="flex items-center">
        <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Sistema Funcionando Correctamente</h3>
            <p class="text-green-600">No hay alertas críticas en este momento</p>
        </div>
    </div>
</div>
@endif

<!-- Recent Orders -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Pedidos Recientes</h3>
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver todos</a>
    </div>

    @if($recentOrders->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentOrders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ url('/orders/' . $order->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            #{{ $order->id }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $order->supplier->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ Carbon\Carbon::parse($order->date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClasses = [
                                'received' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'pending' => 'bg-yellow-100 text-yellow-800'
                            ];
                            $statusLabels = [
                                'received' => 'Recibido',
                                'cancelled' => 'Cancelado',
                                'pending' => 'Pendiente'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${{ number_format($order->total, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $order->items->count() }} productos
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay pedidos recientes</h3>
        <p class="mt-1 text-sm text-gray-500">Los pedidos aparecerán aquí cuando se realicen.</p>
    </div>
    @endif
</div>

<!-- Waste Analysis -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900">Análisis de Desperdicios</h3>
        </div>
        <button onclick="loadWasteReport()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Generar Reporte
        </button>
    </div>
    
    <div id="wasteAnalysis">
        <div id="wasteData" class="mt-4">
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p>Haz clic en "Generar Reporte" para analizar desperdicios</p>
            </div>
        </div>
    </div>
</div>

<script>
// Inventory levels chart
const inventoryLabels = {!! json_encode($ingredients->pluck('name')) !!};
const inventoryStock = {!! json_encode($ingredients->pluck('stock')) !!};
const inventoryMinStock = {!! json_encode($ingredients->pluck('min_stock')) !!};

// Create colors based on stock levels
const inventoryColors = inventoryStock.map((stock, index) => {
    return stock < inventoryMinStock[index] ? '#e74c3c' : '#27ae60';
});

const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
new Chart(inventoryCtx, {
    type: 'bar',
    data: {
        labels: inventoryLabels,
        datasets: [
            {
                label: 'Stock actual',
                data: inventoryStock,
                backgroundColor: inventoryColors,
                borderColor: inventoryColors,
                borderWidth: 1
            },
            {
                label: 'Stock minimo',
                data: inventoryMinStock,
                type: 'line',
                borderColor: '#f39c12',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointBackgroundColor: '#f39c12'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
            },
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Cantidad'
                }
            },
            x: {
                ticks: {
                    maxRotation: 45
                }
            }
        }
    }
});

// Category distribution chart
const categoryLabels = {!! json_encode($categoryAnalysis->keys()) !!};
const categoryValues = {!! json_encode($categoryAnalysis->pluck('total_value')->toArray()) !!};
const categoryColors = ['#3498db', '#e74c3c', '#f39c12', '#27ae60', '#9b59b6'];

const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
        datasets: [{
            data: categoryValues,
            backgroundColor: categoryColors.slice(0, categoryLabels.length),
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
            },
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Waste analysis function
function loadWasteReport() {
    document.getElementById('wasteData').innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Cargando análisis de desperdicios...</span>
        </div>
    `;

    fetch('/api/waste-report')
        .then(response => response.json())
        .then(data => {
            let wasteHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-red-500 text-white p-4 rounded-lg">
                        <div class="text-2xl font-bold">$${data.total_waste_value.toFixed(2)}</div>
                        <div class="text-red-100">Valor total del desperdicio</div>
                    </div>
                    <div class="bg-orange-500 text-white p-4 rounded-lg">
                        <div class="text-2xl font-bold">${data.items_count}</div>
                        <div class="text-orange-100">Productos caducados</div>
                    </div>
                </div>
            `;

            if (data.expired_items.length > 0) {
                wasteHTML += `
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Detalles del Desperdicio</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha caducidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor perdido</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;

                data.expired_items.forEach(item => {
                    const wasteValue = (item.stock * (item.cost || 0)).toFixed(2);
                    wasteHTML += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.stock} ${item.unit}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.expiration_date}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">$${wasteValue}</td>
                        </tr>
                    `;
                });

                wasteHTML += '</tbody></table></div>';
            } else {
                wasteHTML += `
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">¡Excelente!</h3>
                        <p class="mt-1 text-sm text-green-600">No se detectó desperdicio en el inventario.</p>
                    </div>
                `;
            }

            document.getElementById('wasteData').innerHTML = wasteHTML;
        })
        .catch(error => {
            console.error('Error cargando el reporte:', error);
            document.getElementById('wasteData').innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error al cargar el reporte</h3>
                            <p class="text-sm text-red-700 mt-1">No se pudo conectar con el servidor. Inténtalo de nuevo.</p>
                        </div>
                    </div>
                </div>
            `;
        });
}

// Export functions
function exportToPDF() {
    alert('PDF export functionality would be implemented here. Consider using libraries like jsPDF or server-side PDF generation.');
}

function exportToCSV() {
    // Simple CSV export of current inventory
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Name,Category,Stock,Min Stock,Unit,Cost,Supplier,Expiration Date\n";

    {!! json_encode($ingredients) !!}.forEach(item => {
        const row = [
            item.name,
            item.category || '',
            item.stock,
            item.min_stock,
            item.unit,
            item.cost || 0,
            item.supplier ? item.supplier.name : '',
            item.expiration_date || ''
        ].join(',');
        csvContent += row + "\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `tlaix_inventory_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Auto-refresh data every 5 minutes
setInterval(() => {
    location.reload();
}, 300000);
</script>

<style>
@media print {
    nav, button, .print\\:hidden { display: none !important; }
    .bg-white { background: white !important; }
    .shadow-lg { box-shadow: none !important; }
    .rounded-xl { border-radius: 0 !important; }
    body { font-size: 12px; }
    .page-break { page-break-inside: avoid; }
}

/* Custom animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.3s ease-out;
}

/* Chart container improvements */
canvas {
    max-height: 300px;
}
</style>
@endsection
