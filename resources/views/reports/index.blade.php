<!-- Enhanced resources/views/reports/index.blade.php -->
@extends('layouts.app')
@section('title','📊 Reports & Analytics')
@section('content')

<!-- Summary Cards -->
<div class="stats">
    <div class="stat-box" style="background: #27ae60;">
        <span>${{ number_format($totalStockValue, 2) }}</span>
        Total Inventory Value
    </div>
    <div class="stat-box" style="background: {{ count($expired) > 0 ? '#e74c3c' : '#95a5a6' }}">
        <span>{{ count($expired) }}</span>
        Expired Items
    </div>
    <div class="stat-box" style="background: {{ count($lowStock) > 0 ? '#f39c12' : '#95a5a6' }}">
        <span>{{ count($lowStock) }}</span>
        Low Stock Items
    </div>
    <div class="stat-box" style="background: #9b59b6">
        <span>{{ $dishAnalysis['available'] }}/{{ $dishAnalysis['total'] }}</span>
        Available Dishes
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
    <!-- Inventory Levels Chart -->
    <div class="card">
        <h3>📈 Current Inventory Levels</h3>
        <canvas id="inventoryChart" width="400" height="200"></canvas>
        <div style="margin-top: 10px; font-size: 0.9em; color: #7f8c8d;">
            <span style="color: #e74c3c;">●</span> Below minimum stock
            <span style="color: #27ae60; margin-left: 15px;">●</span> Normal stock
        </div>
    </div>

    <!-- Category Distribution -->
    <div class="card">
        <h3>📊 Inventory by Category</h3>
        <canvas id="categoryChart" width="400" height="200"></canvas>
        <div style="margin-top: 15px;">
            @foreach($categoryAnalysis as $category => $data)
            <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                <span><strong>{{ ucfirst($category) }}:</strong></span>
                <span>{{ $data['count'] }} items ({{ number_format($data['total_stock'], 1) }} units)</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Critical Alerts -->
@if(count($expired) > 0 || count($expiringSoon) > 0 || count($lowStock) > 0)
<div class="card" style="margin-top: 20px; border-left: 5px solid #e74c3c;">
    <h3>🚨 Critical Alerts</h3>

    @if(count($expired) > 0)
    <div class="alert alert-error" style="margin: 10px 0;">
        <strong>⚠️ {{ count($expired) }} items have expired:</strong>
        @foreach($expired as $item)
            <div style="margin: 5px 0;">
                • {{ $item->name }} - expired on {{ $item->expiration_date }}
                ({{ $item->stock }} {{ $item->unit }} = ${{ number_format($item->stock * ($item->cost ?? 0), 2) }} value)
            </div>
        @endforeach
    </div>
    @endif

    @if(count($expiringSoon) > 0)
    <div class="alert" style="background: #f39c12; color: white; margin: 10px 0;">
        <strong>⏰ {{ count($expiringSoon) }} items expiring within 3 days:</strong>
        @foreach($expiringSoon as $item)
            <div style="margin: 5px 0;">
                • {{ $item->name }} - expires {{ $item->expiration_date }} ({{ $item->stock }} {{ $item->unit }})
            </div>
        @endforeach
    </div>
    @endif

    @if(count($lowStock) > 0)
    <div class="alert" style="background: #e67e22; color: white; margin: 10px 0;">
        <strong>📉 {{ count($lowStock) }} items below minimum stock:</strong>
        @foreach($lowStock as $item)
            <div style="margin: 5px 0;">
                • {{ $item->name }} - {{ $item->stock }}/{{ $item->min_stock }} {{ $item->unit }}
                @if($item->supplier)
                    (Supplier: {{ $item->supplier->name }})
                @endif
            </div>
        @endforeach
    </div>
    @endif
</div>
@else
<div class="card" style="margin-top: 20px; border-left: 5px solid #27ae60;">
    <h3>✅ All Systems Normal</h3>
    <p style="color: #27ae60;">No critical alerts at this time. All inventory levels are healthy!</p>
</div>
@endif

<!-- Recent Orders -->
<div class="card" style="margin-top: 20px;">
    <h3>📦 Recent Orders</h3>
    @if($recentOrders->count() > 0)
    <table>
        <tr><th>Order ID</th><th>Supplier</th><th>Date</th><th>Status</th><th>Total</th><th>Items</th></tr>
        @foreach($recentOrders as $order)
        <tr>
            <td><a href="{{ url('/orders/' . $order->id) }}">#{{ $order->id }}</a></td>
            <td>{{ $order->supplier->name ?? 'N/A' }}</td>
            <td>{{ Carbon\Carbon::parse($order->date)->format('M d, Y') }}</td>
            <td>
                <span style="color: {{ $order->status == 'received' ? '#27ae60' : ($order->status == 'cancelled' ? '#e74c3c' : '#f39c12') }}">
                    {{ ucfirst($order->status) }}
                </span>
            </td>
            <td>${{ number_format($order->total, 2) }}</td>
            <td>{{ $order->items->count() }} items</td>
        </tr>
        @endforeach
    </table>
    @else
    <p>No recent orders found.</p>
    @endif
</div>

<!-- Waste Analysis -->
<div class="card" style="margin-top: 20px;">
    <h3>🗑️ Waste Analysis</h3>
    <div id="wasteAnalysis">
        <button onclick="loadWasteReport()" class="btn btn-primary">Generate Waste Report</button>
        <div id="wasteData" style="margin-top: 15px;"></div>
    </div>
</div>

<!-- Export Options -->
<div class="card" style="margin-top: 20px;">
    <h3>📤 Export Options</h3>
    <div style="display: flex; gap: 10px;">
        <button onclick="exportToPDF()" class="btn">📄 Export PDF</button>
        <button onclick="exportToCSV()" class="btn">📊 Export CSV</button>
        <button onclick="window.print()" class="btn">🖨️ Print Report</button>
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
                label: 'Current Stock',
                data: inventoryStock,
                backgroundColor: inventoryColors,
                borderColor: inventoryColors,
                borderWidth: 1
            },
            {
                label: 'Minimum Stock',
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
                text: 'Current vs Minimum Stock Levels'
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
                    text: 'Quantity'
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
                text: 'Inventory Value by Category'
            },
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Waste analysis function
function loadWasteReport() {
    document.getElementById('wasteData').innerHTML = '<p>Loading waste analysis...</p>';

    fetch('/api/waste-report')
        .then(response => response.json())
        .then(data => {
            let wasteHTML = `
                <div class="stats" style="margin: 15px 0;">
                    <div class="stat-box" style="background: #e74c3c;">
                        <span>${data.total_waste_value.toFixed(2)}</span>
                        Total Waste Value
                    </div>
                    <div class="stat-box" style="background: #f39c12;">
                        <span>${data.items_count}</span>
                        Expired Items
                    </div>
                </div>
            `;

            if (data.expired_items.length > 0) {
                wasteHTML += '<h4>Detailed Waste Breakdown:</h4><table><tr><th>Item</th><th>Quantity</th><th>Expired Date</th><th>Waste Value</th></tr>';

                data.expired_items.forEach(item => {
                    const wasteValue = (item.stock * (item.cost || 0)).toFixed(2);
                    wasteHTML += `
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.stock} ${item.unit}</td>
                            <td>${item.expiration_date}</td>
                            <td>${wasteValue}</td>
                        </tr>
                    `;
                });

                wasteHTML += '</table>';
            } else {
                wasteHTML += '<p style="color: #27ae60;">✅ No waste detected! All items are within their expiration dates.</p>';
            }

            document.getElementById('wasteData').innerHTML = wasteHTML;
        })
        .catch(error => {
            console.error('Error loading waste report:', error);
            document.getElementById('wasteData').innerHTML = '<p style="color: #e74c3c;">Error loading waste report.</p>';
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
    nav, .card h3, button { display: none !important; }
    .card { page-break-inside: avoid; margin: 10px 0; }
    body { font-size: 12px; }
}
</style>
@endsection
