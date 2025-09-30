@extends('layouts.app')
@section('title','Gestión de Pedidos')
@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-gray-500">Administra los pedidos a proveedores</p>
    </div>
    <a href="{{ route('orders.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-5 rounded-lg transition duration-200 inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Nuevo Pedido
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="card">
    @if($orders->count() > 0)
        <table>
            <tr>
                <th>ID</th>
                <th>Proveedor</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Productos</th>
                <th>Acciones</th>
            </tr>
            @foreach($orders as $order)
            <tr style="border-bottom: 1px solid #e0e0e0;">
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" style="color: #3498db; font-weight: 500;">
                        #{{ $order->id }}
                    </a>
                </td>
                <td>
                    <strong>{{ $order->supplier->name ?? 'Sin proveedor' }}</strong>
                    @if($order->supplier && $order->supplier->contact)
                        <br><small style="color: #7f8c8d;">{{ $order->supplier->contact }}</small>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</td>
                <td>
                    @php
                        $statusColors = [
                            'pending' => '#f39c12',
                            'received' => '#27ae60',
                            'cancelled' => '#e74c3c'
                        ];
                        $statusLabels = [
                            'pending' => 'Pendiente',
                            'received' => 'Recibido',
                            'cancelled' => 'Cancelado'
                        ];
                    @endphp
                    <span style="background: {{ $statusColors[$order->status] }}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.85em; font-weight: 500;">
                        {{ $statusLabels[$order->status] }}
                    </span>
                </td>
                <td style="font-weight: 600; color: #2c3e50;">${{ number_format($order->total, 2) }}</td>
                <td>
                    <span style="background: #ecf0f1; color: #2c3e50; padding: 2px 6px; border-radius: 10px; font-size: 0.9em;">
                        {{ $order->items->count() }} productos
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 5px; align-items: center;">
                        <a href="{{ route('orders.show', $order->id) }}"
                           style="color: #3498db; text-decoration: none; padding: 4px 8px; background: #ebf3fd; border-radius: 3px; font-size: 0.9em;">
                            Ver
                        </a>

                        @if($order->status == 'pending')
                            <form method="POST" action="{{ route('orders.receive', $order->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" onclick="return confirm('¿Marcar como recibido?')"
                                        style="background: #27ae60; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 0.9em; cursor: pointer;">
                                    Recibir
                                </button>
                            </form>

                            <form method="POST" action="{{ route('orders.cancel', $order->id) }}" style="display: inline;">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="return confirm('¿Cancelar este pedido?')"
                                        style="background: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 0.9em; cursor: pointer;">
                                    Cancelar
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #7f8c8d;">
            <div style="font-size: 3em; margin-bottom: 20px;">📦</div>
            <h3>No hay pedidos registrados</h3>
            <p>Comienza creando tu primer pedido a un proveedor</p>
            <a href="{{ route('orders.create') }}" class="btn btn-primary"
               style="background: #27ae60; color: white; padding: 12px 20px; text-decoration: none; border-radius: 4px; margin-top: 15px; display: inline-block;">
                Crear Primer Pedido
            </a>
        </div>
    @endif
</div>

<!-- Resumen de pedidos -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 30px;">
    <div class="stat-box" style="background: #f39c12;">
        <span>{{ $orders->where('status', 'pending')->count() }}</span>
        Pendientes
    </div>
    <div class="stat-box" style="background: #27ae60;">
        <span>{{ $orders->where('status', 'received')->count() }}</span>
        Recibidos
    </div>
    <div class="stat-box" style="background: #e74c3c;">
        <span>{{ $orders->where('status', 'cancelled')->count() }}</span>
        Cancelados
    </div>
    <div class="stat-box" style="background: #3498db;">
        <span>${{ number_format($orders->sum('total'), 2) }}</span>
        Total en Pedidos
    </div>
</div>

<style>
.btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

table tr:hover {
    background-color: #f8f9fa;
}

.stat-box {
    background: #3498db;
    color: white;
    padding: 20px;
    border-radius: 6px;
    text-align: center;
    font-size: 1.1em;
}

.stat-box span {
    display: block;
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 5px;
}

.alert {
    display: flex;
    align-items: center;
}

.alert::before {
    content: "✅";
    margin-right: 10px;
    font-size: 1.2em;
}
</style>
@endsection
