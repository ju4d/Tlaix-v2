@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-[100px]">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 mb-6 transform transition-all duration-200 hover:shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Registro de Mermas</h2>
            
            <!-- Formulario de registro de mermas -->
            <form action="{{ route('waste.store') }}" method="POST" class="mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ingredient_id" class="block text-sm font-semibold text-gray-700 mb-1">Producto</label>
                        <select name="ingredient_id" id="ingredient_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out" required>
                            <option value="">Seleccione un producto</option>
                            @foreach($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" data-cost="{{ $ingredient->cost }}" data-stock="{{ $ingredient->stock }}">
                                    {{ $ingredient->name }} (Stock: {{ number_format($ingredient->stock, 2) }} - Costo: ${{ number_format($ingredient->cost, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <div id="ingredient-details" class="mt-2 text-sm text-gray-600"></div>
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-1">Cantidad</label>
                        <input type="number" name="quantity" id="quantity" step="0.01" min="0.01" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out" required>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-semibold text-gray-700 mb-1">Motivo</label>
                        <select name="reason" id="reason" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out" required>
                            <option value="expired">Producto caducado</option>
                            <option value="damaged_in_storage">Dañado en almacén</option>
                            <option value="customer_return">Devolución de cliente</option>
                            <option value="inventory_error">Error de inventario</option>
                            <option value="theft_loss">Robo/Extravío</option>
                            <option value="internal_use">Uso interno</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>

                    <div>
                        <label for="comments" class="block text-sm font-semibold text-gray-700 mb-1">Comentarios</label>
                        <textarea name="comments" id="comments" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out resize-none"></textarea>
                    </div>
                </div>

                                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:from-blue-700 hover:to-blue-700 active:from-blue-800 active:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-200 ease-in-out hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Registrar Merma
                    </button>
                </div>
            </form>

            <!-- Dashboard de Mermas -->
            <div class="max-w-7xl mx-auto mt-16 px-4 sm:px-6 lg:px-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Dashboard de Mermas</h3>
                <p class="text-gray-600 mb-8">Visualización y análisis de pérdidas por mermas</p>

                <!-- Filtros de fecha -->
                <form action="{{ route('waste.index') }}" method="GET" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date_start" class="block text-sm font-semibold text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="date_start" id="date_start" value="{{ request('date_start', Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out" required>
                        </div>
                        <div>
                            <label for="date_end" class="block text-sm font-semibold text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="date_end" id="date_end" value="{{ request('date_end', Carbon\Carbon::now()->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out" required>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="group bg-gradient-to-r from-blue-500 to-blue-500 hover:from-blue-600 hover:to-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg font-semibold text-xs uppercase tracking-widest">
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>

                <!-- KPIs -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                        <!-- Tarjeta 1 - Pérdida Total Sin IVA -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-500 text-white p-6 rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105 hover:shadow-xl">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-blue-100 truncate">
                                            Pérdida Total (Sin IVA)
                                        </dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-white">
                                                ${{ number_format($totalLoss, 2) }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta 2 - IVA -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-500 text-white p-6 rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105 hover:shadow-xl">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-purple-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-purple-100 truncate">
                                            IVA
                                        </dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-white">
                                                ${{ number_format($totalTax, 2) }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta 3 - Total con IVA -->
                        <div class="bg-gradient-to-r from-orange-500 to-orange-500 text-white p-6 rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105 hover:shadow-xl">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-orange-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-orange-100 truncate">
                                            Pérdida Total (Con IVA)
                                        </dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-white">
                                                ${{ number_format($totalWithTax, 2) }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico y Detalles -->
                <div class="max-w-7xl mx-auto mt-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-6 mb-8">
                        <div class="bg-white p-8 rounded-lg shadow-lg transform transition-all duration-200 hover:shadow-xl">
                            <h4 class="text-2xl font-bold text-gray-800 mb-6">Detalles por Causa</h4>
                        <div id="wasteDetails" class="overflow-y-auto max-h-96">
                            @foreach($wasteByReason as $reason => $data)
                                <div class="mb-6 p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 bg-white">
                                    <h5 class="font-bold text-lg text-gray-800 mb-3 flex items-center">
                                        @switch($reason)
                                            @case('expired')
                                                Producto caducado
                                                @break
                                            @case('damaged_in_storage')
                                                Dañado en almacén
                                                @break
                                            @case('customer_return')
                                                Devolución de cliente
                                                @break
                                            @case('inventory_error')
                                                Error de inventario
                                                @break
                                            @case('theft_loss')
                                                Robo/Extravío
                                                @break
                                            @case('internal_use')
                                                Uso interno
                                                @break
                                            @default
                                                Otro
                                        @endswitch
                                    </h5>
                                    <div class="flex items-center justify-between mb-4 bg-gray-50 p-3 rounded-lg">
                                        <p class="text-gray-600">
                                            <span class="font-semibold text-gray-800">Total:</span>
                                            <span class="text-blue-600 font-bold">${{ number_format($data['total'], 2) }}</span>
                                        </p>
                                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                                            {{ $data['count'] }} registros
                                        </span>
                                    </div>
                                    <div class="space-y-3">
                                        @foreach($data['records'] as $record)
                                            <div class="text-sm p-4 bg-gray-50 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors duration-200">
                                                <div class="grid grid-cols-2 gap-3">
                                                    <p class="flex items-center">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span class="font-medium text-gray-700">{{ $record['date'] }}</span>
                                                    </p>
                                                    <p class="flex items-center">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                        </svg>
                                                        <span class="font-medium text-gray-700">{{ $record['ingredient'] }}</span>
                                                    </p>
                                                    <p class="flex items-center">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                                                        </svg>
                                                        <span class="font-medium text-gray-700">{{ $record['quantity'] }}</span>
                                                    </p>
                                                    <p class="flex items-center">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span class="font-medium text-blue-600">${{ number_format($record['total_cost'], 2) }}</span>
                                                    </p>
                                                </div>
                                                @if($record['comments'])
                                                    <p class="mt-3 pt-3 border-t border-gray-200 flex items-start">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                        </svg>
                                                        <span class="font-medium text-gray-700">{{ $record['comments'] }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Tabla de registros -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-all duration-200 hover:shadow-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Motivo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pérdida Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($wasteRecords as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $record->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $record->ingredient->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $record->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @switch($record->reason)
                                            @case('expired')
                                                Producto caducado
                                                @break
                                            @case('damaged_in_storage')
                                                Dañado en almacén
                                                @break
                                            @case('customer_return')
                                                Devolución de cliente
                                                @break
                                            @case('inventory_error')
                                                Error de inventario
                                                @break
                                            @case('theft_loss')
                                                Robo/Extravío
                                                @break
                                            @case('internal_use')
                                                Uso interno
                                                @break
                                            @default
                                                Otro
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($record->total_cost, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wasteData = @json($wasteByReason);
        const wasteReasonCtx = document.getElementById('wasteReasonChart').getContext('2d');

        if (Object.keys(wasteData).length > 0) {
            const reasons = Object.keys(wasteData);
            const totals = reasons.map(reason => wasteData[reason].total);
            const translations = {
                'expired': 'Producto caducado',
                'damaged_in_storage': 'Dañado en almacén',
                'customer_return': 'Devolución de cliente',
                'inventory_error': 'Error de inventario',
                'theft_loss': 'Robo/Extravío',
                'internal_use': 'Uso interno',
                'other': 'Otro'
            };
        
        const labels = [];
        const values = [];
        
        Object.entries(wasteData).forEach(([key, value]) => {
            labels.push(translateReason(key));
            values.push(parseFloat(value));
        });
        
        new Chart(wasteReasonCtx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#C9CBCF'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                return `${label}: $${value.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
    } else {
        wasteReasonCtx.canvas.style.display = 'none';
        wasteReasonCtx.canvas.parentNode.innerHTML = '<p class="text-gray-500 text-center mt-4">No hay datos de mermas para mostrar en este período</p>';
    }

            new Chart(wasteReasonCtx, {
                type: 'pie',
                data: {
                    labels: reasons.map(r => translations[r] || r),
                    datasets: [{
                        data: totals,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40',
                            '#C9CBCF'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value * 100) / total).toFixed(1);
                                    return `$${value.toFixed(2)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });    // Validación dinámica del formulario
    document.getElementById('reason').addEventListener('change', function() {
        const commentsField = document.getElementById('comments');
        if (this.value === 'other') {
            commentsField.setAttribute('required', 'required');
        } else {
            commentsField.removeAttribute('required');
        }
    });

    // Mostrar información del producto seleccionado
    document.getElementById('ingredient_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const detailsDiv = document.getElementById('ingredient-details');
        
        if (selectedOption.value) {
            const cost = parseFloat(selectedOption.dataset.cost);
            const stock = parseFloat(selectedOption.dataset.stock);
            
            if (!cost || cost <= 0) {
                detailsDiv.innerHTML = '<span class="text-red-600">⚠️ Advertencia: Este ingrediente no tiene un costo válido registrado.</span>';
                return;
            }
            
            const quantity = document.getElementById('quantity');
            const updateTotal = () => {
                const qtyValue = parseFloat(quantity.value) || 0;
                const total = qtyValue * cost;
                detailsDiv.innerHTML = `
                    <p>Costo por unidad: $${cost.toFixed(2)}</p>
                    <p>Stock disponible: ${stock.toFixed(2)}</p>
                    <p class="font-semibold">Pérdida total estimada: $${total.toFixed(2)}</p>
                `;
            };
            
            updateTotal();
            quantity.addEventListener('input', updateTotal);
        } else {
            detailsDiv.innerHTML = '';
        }
    });
</script>
@endpush
