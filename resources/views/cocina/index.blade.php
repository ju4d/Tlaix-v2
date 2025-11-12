@extends('layouts.app')
@section('title', 'Cocina')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 -m-6 p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="bg-orange-500 p-3 rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Cocina</h1>
                    <p class="text-gray-600">Gestión de órdenes en tiempo real</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-white rounded-xl shadow-lg px-6 py-3">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600" id="pending-count">{{ $orders->count() }}</p>
                        <p class="text-xs text-gray-600">Órdenes activas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Órdenes Activas -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Órdenes en Preparación
            </h2>
            <button onclick="location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg shadow transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Actualizar
            </button>
        </div>

        <div id="orders-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($orders as $order)
                <div class="order-card bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" data-order-id="{{ $order->id }}">
                    <!-- Header de la orden -->
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-white bg-opacity-30 backdrop-blur-sm rounded-lg px-3 py-1">
                                    <span class="text-white font-bold text-lg">#{{ $order->id }}</span>
                                </div>
                            </div>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="delete-order-form" onsubmit="return confirm('¿Seguro que deseas eliminar esta orden?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-lg transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Platillos -->
                    <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                        @php
                            $totalItems = $order->dishes->count();
                            $completedItems = $order->dishes->where('completed', true)->count();
                            $progress = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
                        @endphp

                        @foreach($order->dishes as $item)
                            <div class="dish-item {{ $item->completed ? 'opacity-60' : '' }} bg-gray-50 rounded-xl p-4 border-2 {{ $item->completed ? 'border-green-200' : 'border-orange-200' }} transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="bg-{{ $item->completed ? 'green' : 'orange' }}-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow-lg">
                                            {{ $item->quantity }}
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 {{ $item->completed ? 'line-through' : '' }}">
                                                {{ $item->dish->name }}
                                            </h4>
                                            @if($item->completed)
                                                <p class="text-xs text-green-600 font-medium flex items-center mt-1">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Completado
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        @if(!$item->completed)
                                            <form action="{{ route('cocina.complete', [$order->id, $item->id]) }}" method="POST" class="complete-dish-form">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl shadow-lg transition duration-200 flex items-center space-x-2 transform hover:scale-105">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="font-medium">Listo</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-xl flex items-center space-x-2">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium text-sm">✓</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Barra de progreso -->
                    <div class="px-6 pb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-gray-600">Progreso</span>
                            <span class="text-xs font-bold text-gray-900">{{ $completedItems }}/{{ $totalItems }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500 shadow-inner" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">¡Todo al día!</h3>
                        <p class="text-gray-600">No hay órdenes pendientes en este momento</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Histórico -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-700 to-gray-900 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Platillos Completados Hoy
            </h2>
        </div>
        <div id="historico-list" class="p-6">
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($historico as $item)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition duration-200 border border-gray-200">
                        <div class="flex items-center space-x-4">
                            <div class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow">
                                {{ $item->quantity }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $item->dish ? $item->dish->name : 'Platillo eliminado' }}</h4>
                                <p class="text-xs text-gray-500">Orden #{{ $item->customer_order_id }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700">{{ $item->updated_at->format('H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $item->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500 font-medium">No hay platillos completados aún</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
/* Animaciones personalizadas */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.order-card {
    animation: slideIn 0.3s ease-out;
}

/* Scrollbar personalizado */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animación de pulso mejorada */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Transición suave para completar platillos */
.dish-item {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Optimización para tablets */
@media (min-width: 768px) and (max-width: 1024px) {
    .order-card {
        font-size: 1.1rem;
    }
    
    button {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    
    .dish-item {
        padding: 1.25rem;
    }
}

/* Modo táctil mejorado */
@media (hover: none) {
    button:active {
        transform: scale(0.95);
    }
    
    .order-card:active {
        transform: scale(0.98);
    }
}
</style>

<script>
// Auto-refresh cada 30 segundos
setInterval(() => {
    location.reload();
}, 30000);

// Confirmación visual al completar platillo
document.querySelectorAll('.complete-dish-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const button = this.querySelector('button');
        button.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        button.disabled = true;
    });
});

// Sonido de notificación (opcional)
function playNotificationSound() {
    // Puedes agregar un audio si lo deseas
    // const audio = new Audio('/sounds/notification.mp3');
    // audio.play();
}
</script>

@endsection
