<!-- Enhanced resources/views/dashboard.blade.php with Real-Time Predictions -->
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Stats Grid -->
<div class="stats">
    <div class="stat-box">
        <span>{{ $totalIngredients }}</span>
        Insumos Totales
    </div>
    <div class="stat-box" style="background: {{ $lowStock > 0 ? '#e74c3c' : '#27ae60' }}">
        <span>{{ $lowStock }}</span>
        Insumos bajos
    </div>
    <div class="stat-box" style="background: #f39c12">
        <span>{{ $totalDishes }}</span>
        Platillos Totales
    </div>
    <div class="stat-box" style="background: #9b59b6">
        <span>{{ $availableDishes }}</span>
        Platillos Disponibles
    </div>
    <div class="stat-box" style="background: #B2C8DFFF">
        <span>{{ $pendingOrders }}</span>
        Pedidos Pendientes
    </div>
</div>

<!-- Two Column Layout -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
    <!-- Critical Stock Items -->
    <div class="card">
        <h3>Stock Crítico</h3>
        @if($lowStockItems->count() > 0)
            <table>
                <tr><th>Ingrediente</th><th>Actual</th><th>Mínimo</th><th>Estado</th></tr>
                @foreach($lowStockItems as $item)
                    <tr class="{{ $item->stock < $item->min_stock ? 'low-stock' : '' }}">
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->stock }} {{ $item->unit }}</td>
                        <td>{{ $item->min_stock }}</td>
                        <td>
                            @if($item->stock < $item->min_stock)
                                <span style="color: #e74c3c; font-weight: bold;">CRÍTICO</span>
                            @else
                                <span style="color: #f39c12;">LOW</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <p style="color: #27ae60;">✅ Todos los ingredientes cuentan con buen stock</p>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h3>Acciones Rápidas</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('inventory.create') }}" class="quick-action-btn">➕ Agregar Nuevo Ingrediente</a>
            <a href="{{ route('dishes.create') }}" class="quick-action-btn">🍽️ Crear Nuevo Platillo</a>
            <a href="{{ route('orders.index') }}" class="quick-action-btn">📦 Consultar Pedidos</a>
            <a href="{{ route('reports') }}" class="quick-action-btn">📊 Generar Reportes</a>
        </div>
    </div>
</div>

<!-- Real-Time Demand Statistics -->
<div class="card" style="margin-top: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h3>📊 Estadísticas de Demanda en Tiempo Real</h3>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button onclick="openRecordModal()" class="btn btn-success" style="background: #27ae60;">
                📝 Registrar Demanda
            </button>
            <button onclick="autoRecordDemand()" class="btn" style="background: #f39c12;">
                🔄 Auto-registrar Hoy
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
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
        <div class="mini-stat-card" style="border-left: 4px solid #3498db;">
            <div class="mini-stat-label">Demanda Hoy</div>
            <div class="mini-stat-value" id="demandToday">-</div>
            <small>unidades</small>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #27ae60;">
            <div class="mini-stat-label">Demanda Semanal</div>
            <div class="mini-stat-value" id="demandWeek">-</div>
            <small>unidades</small>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #f39c12;">
            <div class="mini-stat-label">Demanda Mensual</div>
            <div class="mini-stat-value" id="demandMonth">-</div>
            <small>unidades</small>
        </div>
        <div class="mini-stat-card" style="border-left: 4px solid #9b59b6;">
            <div class="mini-stat-label">Promedio Diario</div>
            <div class="mini-stat-value" id="demandAvg">-</div>
            <small>unidades</small>
        </div>
    </div>
</div>

<!-- Ingredient Predictions -->
<div class="card" style="margin-top: 20px;">
    <h3>🔮 Predicciones de Reabastecimiento por Ingrediente</h3>
    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 15px; flex-wrap: wrap;">
        <button onclick="loadIngredientPredictions(7)" class="btn btn-primary">7 días</button>
        <button onclick="loadIngredientPredictions(14)" class="btn">14 días</button>
        <button onclick="loadIngredientPredictions(30)" class="btn">30 días</button>
        <button onclick="loadSuggestedOrders()" class="btn" style="background: #27ae60; margin-left: auto;">
            📋 Ver Órdenes Sugeridas
        </button>
        <span id="loadingIngredients" style="display: none; color: #3498db;">⏳ Calculando...</span>
    </div>

    <!-- Summary Cards -->
    <div id="predictionSummary" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 20px;"></div>

    <!-- Predictions Table -->
    <div id="ingredientPredictions"></div>
</div>

<!-- Recent Activity -->
<div class="card" style="margin-top: 20px;">
    <h3>📋 Actividad Reciente y Pendientes</h3>
    <div id="recentActivity">
        @if($pendingOrders > 0)
            <div class="activity-item">
                <span class="activity-icon">📦</span>
                <span>{{ $pendingOrders }} Órdenes pendientes</span>
                <span class="activity-time">Ahora</span>
            </div>
        @endif

        @if($lowStock > 0)
            <div class="activity-item">
                <span class="activity-icon">⚠️</span>
                <span>{{ $lowStock }} ingredientes bajo el mínimo</span>
                <span class="activity-time">Ahora</span>
            </div>
        @endif

        <div class="activity-item">
            <span class="activity-icon">✅</span>
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
                    💾 Guardar
                </button>
                <button type="button" class="btn" onclick="closeRecordModal()" style="flex: 1; background: #95a5a6; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer;">
                    ✖ Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Suggested Orders Modal -->
<div id="ordersModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">📋 Órdenes de Compra Sugeridas</h2>
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
        console.log('📊 Datos actualizados automáticamente');
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
            showAlert('✅ Demanda registrada correctamente', 'success');
            closeRecordModal();
            loadDemandSummary();
        } else {
            showAlert('❌ Error: ' + (data.error || 'Error desconocido'), 'error');
        }
    } catch (error) {
        showAlert('❌ Error de conexión: ' + error.message, 'error');
    }
}

// Auto-registrar demanda del día
async function autoRecordDemand() {
    try {
        showAlert('🔄 Registrando demanda automáticamente...', 'success');

        const response = await fetch('/api/demand/auto-record', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            if (data.recorded) {
                showAlert(`✅ Demanda registrada: ${data.quantity} unidades`, 'success');
            } else {
                showAlert('ℹ️ No hay datos para registrar hoy', 'success');
            }
            loadDemandSummary();
        } else {
            showAlert('❌ Error: ' + (data.error || 'No hay datos para registrar'), 'error');
        }
    } catch (error) {
        showAlert('❌ Error: ' + error.message, 'error');
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
                <h4 style="margin: 0 0 10px 0;">⚠️ ${needsRestock.length} Ingrediente(s) necesitan reabastecimiento en los próximos ${days} días</h4>
            </div>
        `;

        html += '<h4>🚨 Ingredientes Críticos y Prioritarios</h4>';
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
                'critical': '🔴 CRÍTICO',
                'high': '🟠 ALTO',
                'medium': '🟡 MEDIO'
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
                <h4 style="margin: 0; color: #27ae60;">✅ Todos los ingredientes tienen stock suficiente para los próximos ${days} días</h4>
            </div>
        `;
    }

    // Sección de ingredientes con stock normal (colapsable)
    if (others.length > 0) {
        html += `
            <div style="margin-top: 30px;">
                <h4 style="cursor: pointer;" onclick="toggleOthers()">
                    📊 Otros Ingredientes con Stock Normal (${others.length})
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
                    <td><span class="urgency-normal">✅ OK</span></td>
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
                <div style="font-size: 3em; margin-bottom: 20px;">✅</div>
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
        const supplierIcon = order.supplier_id ? '🏢' : '⚠️';
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
                'critical': '🔴',
                'high': '🟠',
                'medium': '🟡',
                'low': '🟢',
                'normal': '✅'
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
                    <div style="margin-top: 15px; text-align: right;">
                        <a href="/orders/create?supplier_id=${order.supplier_id}"
                           class="btn btn-success"
                           style="background: #27ae60; padding: 10px 20px; color: white; text-decoration: none; display: inline-block; border-radius: 4px;">
                            📦 Crear Pedido
                        </a>
                    </div>
                ` : `
                    <div style="margin-top: 15px; padding: 10px; background: #fff3cd; border-radius: 4px;">
                        <small style="color: #856404;">
                            ⚠️ Asigna un proveedor a estos ingredientes para poder crear el pedido automáticamente.
                        </small>
                    </div>
                `}
            </div>
        `;
    });

    html += `
        <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="exportOrdersToCSV()" class="btn" style="background: #3498db;">
                📄 Exportar CSV
            </button>
            <button onclick="window.print()" class="btn" style="background: #95a5a6;">
                🖨️ Imprimir
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

            showAlert('✅ CSV exportado correctamente', 'success');
        })
        .catch(error => {
            showAlert('❌ Error al exportar: ' + error.message, 'error');
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
</script>
@endsection
