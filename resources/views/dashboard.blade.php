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

<!-- Demand Predictions -->
<div class="card" style="margin-top: 20px;">
    <h3>🔮 Predicciones de Tlaix IA</h3>
    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 15px;">
        <button onclick="loadPredictions(7)" class="btn btn-primary">7 días</button>
        <button onclick="loadPredictions(14)" class="btn">14 días</button>
        <button onclick="loadPredictions(30)" class="btn">30 días</button>
        <span id="loading" style="display: none; color: #3498db;">⏳ Cargando predicciones...</span>
    </div>
    <canvas id="predictionChart" width="800" height="300"></canvas>
    <div id="predictionData" style="margin-top: 15px;"></div>
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
    margin: 10% auto;
    padding: 30px;
    border-radius: 8px;
    max-width: 500px;
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
.confidence-high { color: #27ae60; font-weight: bold; }
.confidence-medium { color: #f39c12; font-weight: bold; }
.confidence-low { color: #e74c3c; font-weight: bold; }
</style>

<script>
let predictionChart = null;
let autoRefreshInterval = null;

// Configurar fecha por defecto
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('demandDate').valueAsDate = new Date();

    // Cargar datos iniciales
    loadDemandSummary();
    loadPredictions(7);

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

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('recordModal');
    if (event.target == modal) {
        closeRecordModal();
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
            loadPredictions(7);
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
            loadPredictions(7);
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

// Cargar predicciones
function loadPredictions(days) {
    document.getElementById('loading').style.display = 'inline';

    fetch(`/api/predictions/${days}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';

            if (data.predictions) {
                displayPredictionChart(data.predictions);
                displayPredictionTable(data);
            } else {
                console.error('No predictions data received');
            }
        })
        .catch(error => {
            document.getElementById('loading').style.display = 'none';
            console.error('Error loading predictions:', error);
            document.getElementById('predictionData').innerHTML =
                '<p style="color: #e74c3c;">Error al cargar las predicciones. Verifica que Python y las dependencias estén instaladas.</p>';
        });
}

// Mostrar gráfico de predicciones
function displayPredictionChart(predictions) {
    const ctx = document.getElementById('predictionChart').getContext('2d');

    if (predictionChart) {
        predictionChart.destroy();
    }

    const dates = predictions.map(p => new Date(p.date).toLocaleDateString('es-ES', { month: 'short', day: 'numeric' }));
    const demands = predictions.map(p => p.demand);

    predictionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Demanda Predicha',
                data: demands,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#3498db',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Predicciones de demanda para los próximos días',
                    font: { size: 14 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Unidades necesarias'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Fecha'
                    }
                }
            }
        }
    });
}

// Mostrar tabla de predicciones
function displayPredictionTable(data) {
    const predictions = data.predictions;
    const modelInfo = data.model_info;

    let html = '';

    // Información del modelo
    if (modelInfo) {
        html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 15px;">';
        html += '<strong>📊 Información del Modelo:</strong><br>';
        html += `<small>Datos de entrenamiento: ${modelInfo.training_samples || 0} registros</small><br>`;
        if (modelInfo.last_update) {
            html += `<small>Última actualización: ${new Date(modelInfo.last_update).toLocaleDateString('es-ES')}</small><br>`;
        }
        if (modelInfo.mean_demand) {
            html += `<small>Demanda promedio: ${modelInfo.mean_demand.toFixed(1)} unidades</small>`;
        }
        if (modelInfo.note) {
            html += `<br><small style="color: #f39c12;">⚠️ ${modelInfo.note}</small>`;
        }
        html += '</div>';
    }

    // Tabla de predicciones
    html += '<h4>Predicción detallada</h4>';
    html += '<table class="prediction-table">';
    html += '<thead><tr><th>Fecha</th><th>Día</th><th>Demanda Predicha</th><th>Confianza</th><th>Recomendación</th></tr></thead>';
    html += '<tbody>';

    predictions.forEach(p => {
        const date = new Date(p.date);
        const dayName = date.toLocaleDateString('es-ES', { weekday: 'long' });
        const demand = parseFloat(p.demand);
        const confidence = p.confidence || 'medium';

        let recommendation = '';
        let rowClass = '';

        if (demand > 25) {
            recommendation = '🔴 Alta demanda - Mantener stock alto';
            rowClass = 'style="background: #fff3cd;"';
        } else if (demand > 15) {
            recommendation = '🟡 Demanda normal esperada';
            rowClass = 'style="background: #d1ecf1;"';
        } else {
            recommendation = '🟢 Baja demanda - No hacer pedidos grandes';
            rowClass = 'style="background: #d4edda;"';
        }

        const confidenceClass = `confidence-${confidence}`;
        const confidenceText = {
            'high': 'Alta',
            'medium': 'Media',
            'low': 'Baja'
        }[confidence] || 'Media';

        html += `<tr ${rowClass}>`;
        html += `<td>${date.toLocaleDateString('es-ES')}</td>`;
        html += `<td style="text-transform: capitalize;">${dayName}</td>`;
        html += `<td><strong>${demand.toFixed(1)}</strong> unidades</td>`;
        html += `<td class="${confidenceClass}">${confidenceText}</td>`;
        html += `<td>${recommendation}</td>`;
        html += '</tr>';
    });

    html += '</tbody></table>';

    document.getElementById('predictionData').innerHTML = html;
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
