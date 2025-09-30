@extends('layouts.app')
@section('title','Detalles del Pedido')
@section('content')

<div class="max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold text-gray-900">Pedido #{{ $order->id }}</h2>
            <div class="flex items-center space-x-3">
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
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Order Information -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Pedido</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Proveedor</dt>
                <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $order->supplier->name ?? 'N/A' }}</dd>
                @if($order->supplier && $order->supplier->contact)
                    <dd class="text-sm text-gray-500">{{ $order->supplier->contact }}</dd>
                @endif
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Fecha del Pedido</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Productos</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->items->count() }} artículos</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Total</dt>
                <dd class="mt-1 text-lg font-bold text-gray-900">${{ number_format($order->total, 2) }}</dd>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Productos del Pedido</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ingrediente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo Unitario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->ingredient->name ?? 'N/A' }}</div>
                            @if($item->ingredient && $item->ingredient->category)
                                <div class="text-sm text-gray-500">{{ ucfirst($item->ingredient->category) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->quantity }} {{ $item->ingredient->unit ?? '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($item->unit_cost, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                            Total del Pedido:
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                            ${{ number_format($order->total, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('orders.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a Pedidos
        </a>

        @if($order->status == 'pending')
            <div class="flex space-x-3">
                <form method="POST" action="{{ url('/orders/'.$order->id.'/receive') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('¿Confirmar que se recibió este pedido?')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Marcar como Recibido
                    </button>
                </form>
                
                <form method="POST" action="{{ route('orders.cancel', $order->id) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            onclick="return confirm('¿Confirmar cancelación de este pedido?')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar Pedido
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@endsection
