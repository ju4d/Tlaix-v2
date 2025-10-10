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

@if($orders->count() > 0)
    <!-- Lista de Pedidos como Cards -->
    <div class="space-y-4">
        @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <!-- ID y Estado -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-xl font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                Pedido #{{ $order->id }}
                            </a>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'received' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pendiente',
                                    'received' => 'Recibido',
                                    'cancelled' => 'Cancelado'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </div>
                        
                        <!-- Totales -->
                        <div class="text-right">
                            <div class="mb-1">
                                <p class="text-xs text-gray-500">Subtotal</p>
                                <p class="text-sm text-gray-700">${{ number_format($order->total, 2) }}</p>
                            </div>
                            <div class="mb-1">
                                <p class="text-xs text-gray-500">IVA (16%)</p>
                                <p class="text-sm text-gray-700">${{ number_format($order->total * 0.16, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format($order->total * 1.16, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información Principal -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Proveedor -->
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Proveedor</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->supplier->name ?? 'Sin proveedor' }}</p>
                            @if($order->supplier && $order->supplier->contact)
                                <p class="text-sm text-gray-600">{{ $order->supplier->contact }}</p>
                            @endif
                        </div>

                        <!-- Fecha -->
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Fecha del Pedido</p>
                            <p class="text-lg text-gray-900">{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->date)->diffForHumans() }}</p>
                        </div>

                        <!-- Productos -->
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Productos</p>
                            <p class="text-lg text-gray-900">{{ $order->items->count() }} artículos</p>
                            @if($order->items->count() > 0)
                                <p class="text-sm text-gray-600">{{ $order->items->first()->ingredient->name ?? 'N/A' }}{{ $order->items->count() > 1 ? ' y más...' : '' }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('orders.show', $order->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Detalles
                            </a>

                            @if($order->status == 'pending')
                                <form method="POST" action="{{ route('orders.receive', $order->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('¿Marcar como recibido?')"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Recibir
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('orders.cancel', $order->id) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                            onclick="return confirm('¿Cancelar este pedido?')"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancelar
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Indicador de urgencia para pedidos pendientes -->
                        @if($order->status == 'pending')
                            @php
                                $daysSinceOrder = \Carbon\Carbon::parse($order->date)->diffInDays(now());
                            @endphp
                            @if($daysSinceOrder > 7)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $daysSinceOrder }} días
                                </span>
                            @elseif($daysSinceOrder > 3)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $daysSinceOrder }} días
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
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

<!-- Resumen de Pedidos -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen de Pedidos</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pendientes</p>
                    <p class="text-3xl font-bold">{{ $orders->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-yellow-500 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-yellow-100 text-sm">
                    <span>Requieren atención</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Recibidos</p>
                    <p class="text-3xl font-bold">{{ $orders->where('status', 'received')->count() }}</p>
                </div>
                <div class="bg-green-500 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-green-100 text-sm">
                    <span>Completados exitosamente</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-400 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Cancelados</p>
                    <p class="text-3xl font-bold">{{ $orders->where('status', 'cancelled')->count() }}</p>
                </div>
                <div class="bg-red-500 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-red-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-red-100 text-sm">
                    <span>No completados</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Invertido</p>
                    <p class="text-3xl font-bold">${{ number_format($orders->whereNotIn('status', ['cancelled'])->sum('total') * 1.16, 2) }}</p>
                </div>
                <div class="bg-blue-500 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-blue-100 text-sm">
                    <span>En todos los pedidos</span>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
