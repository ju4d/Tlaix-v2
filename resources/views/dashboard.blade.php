<!-- Enhanced resources/views/dashboard.blade.php -->
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
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
        Platilos Totales
    </div>
    <div class="stat-box" style="background: #9b59b6">
        <span>{{ $availableDishes }}</span>
        Platilos Disponibles
    </div>
    <div class="stat-box" style="background: #B2C8DFFF">
        <span>{{ $pendingOrders }}</span>
        Pedidos Pendientes
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
    <!-- Critical Stock Items -->
    <div class="card">
        <h3>Stock critico</h3>
        @if($lowStockItems->count() > 0)
            <table>
                <tr><th>Ingrediente</th><th>Actual</th><th>Minimo</th><th>Estado</th></tr>
                @foreach($lowStockItems as $item)
                    <tr class="{{ $item->stock < $item->min_stock ? 'low-stock' : '' }}">
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->stock }} {{ $item->unit }}</td>
                        <td>{{ $item->min_stock }}</td>
                        <td>
                            @if($item->stock < $item->min_stock)
                                <span style="color: #e74c3c; font-weight: bold;">CRITICO</span>
                            @else
                                <span style="color: #f39c12;">LOW</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <p style="color: #27ae60;">Todos los ingredientes cuentan con buen stock</p>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h3>Acciones rapidas</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('inventory.create') }}" class="quick-action-btn">Agregar nuevo ingredients</a>
            <a href="{{ route('dishes.create') }}" class="quick-action-btn">Crear nuevo platilos</a>
            <a href="{{ route('orders.index') }}" class="quick-action-btn">Consultar pedidos</a>
            <a href="{{ route('reports') }}" class="quick-action-btn">Generar reportes</a>
        </div>
    </div>
</div>

<!-- Demand Predictions -->
<div class="card" style="margin-top: 20px;">
    <h3>Predicciones de Tlaix IA</h3>
    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 15px;">
        <button onclick="loadPredictions(7)" class="btn btn-primary">7 dias</button>
        <button onclick="loadPredictions(14)" class="btn">14 dias</button>
        <button onclick="loadPredictions(30)" class="btn">30 dias</button>
        <span id="loading" style="display: none; color: #3498db;">Cargando predicciones...</span>
    </div>
    <canvas id="predictionChart" width="800" height="300"></canvas>
    <div id="predictionData" style="margin-top: 15px;"></div>
</div>

<!-- Recent Activity -->
<div class="card" style="margin-top: 20px;">
    <h3>Atividad reciente y pendientes</h3>
    <div id="recentActivity">
        @if($pendingOrders > 0)
            <div class="activity-item">
                <span class="activity-icon">📦</span>
                <span>{{ $pendingOrders }} Ordenes pendientes</span>
                <span class="activity-time">Ahora</span>
            </div>
        @endif

        @if($lowStock > 0)
            <div class="activity-item">
                <span class="activity-icon">⚠️</span>
                <span>{{ $lowStock }} ingredientes bajo el minimo</span>
                <span class="activity-time">Ahora</span>
            </div>
        @endif

        <div class="activity-item">
            <span class="activity-icon">✅</span>
            <span>Dashboards cargados</span>
            <span class="activity-time">Justo ahora</span>
        </div>
    </div>
</div>

<style>
.quick-action-btn {
    display: block;
    padding: 12px 15px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    text-align: center;
    transition: background 0.2s ease;
}
.quick-action-btn:hover {
    background: #2980b9;
}
.btn {
    padding: 8px 16px;
    background: #95a5a6;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.2s ease;
}
.btn-primary {
    background: #3498db;
}
.btn:hover {
    opacity: 0.8;
}
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
</style>

<script>
let predictionChart = null;

function loadPredictions(days) {
    document.getElementById('loading').style.display = 'inline';

    fetch(`/api/predictions/${days}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';

            if (data.predictions) {
                displayPredictionChart(data.predictions);
                displayPredictionTable(data.predictions);
            } else {
                console.error('No predictions data received');
            }
        })
        .catch(error => {
            document.getElementById('loading').style.display = 'none';
            console.error('Error loading predictions:', error);
            document.getElementById('predictionData').innerHTML =
                '<p style="color: #e74c3c;">Error al cargar las predicciones (Revisar dependencias de python).</p>';
        });
}

function displayPredictionChart(predictions) {
    const ctx = document.getElementById('predictionChart').getContext('2d');

    if (predictionChart) {
        predictionChart.destroy();
    }

    const dates = predictions.map(p => new Date(p.date).toLocaleDateString());
    const demands = predictions.map(p => p.demand);

    predictionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Predicted Demand',
                data: demands,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Demand Forecast'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Demand Units'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });
}

function displayPredictionTable(predictions) {
    let tableHTML = '<h4>Detailed Predictions</h4><table><tr><th>Date</th><th>Predicted Demand</th><th>Recommendation</th></tr>';

    predictions.forEach(p => {
        const demand = parseFloat(p.demand);
        let recommendation = '';
        let rowClass = '';

        if (demand > 25) {
            recommendation = '📈 High demand expected - stock up';
            rowClass = 'style="background: #fff3cd;"';
        } else if (demand > 15) {
            recommendation = '📊 Normal demand expected';
            rowClass = 'style="background: #d1ecf1;"';
        } else {
            recommendation = '📉 Low demand expected - reduce prep';
            rowClass = 'style="background: #f8d7da;"';
        }

        tableHTML += `<tr ${rowClass}>
            <td>${new Date(p.date).toLocaleDateString()}</td>
            <td>${demand.toFixed(1)} units</td>
            <td>${recommendation}</td>
        </tr>`;
    });

    tableHTML += '</table>';
    document.getElementById('predictionData').innerHTML = tableHTML;
}

// Load initial predictions on page load
document.addEventListener('DOMContentLoaded', function() {
    loadPredictions(7);
});
</script>
@endsection
