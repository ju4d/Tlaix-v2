@extends('layouts.app')
@section('title', 'Mesero')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 -m-6 p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-500 p-3 rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de órdenes del mesero</h1>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg px-6 py-3">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600" id="order-count">{{ $orders->count() }}</p>
                    <p class="text-xs text-gray-600">Órdenes activas</p>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-4 shadow-lg animate-shake">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-bold text-red-800 mb-2">Error al crear la orden:</h3>
                        @foreach($errors->all() as $error)
                            <p class="text-red-700 text-sm">• {{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-4 shadow-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-4 shadow-lg animate-slideIn">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel de Selección de Platillos -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nueva Orden
                    </h2>
                </div>

                <form action="{{ route('mesero.orders.store') }}" method="POST" id="orderForm" class="p-6">
                    @csrf
                    
                    <!-- Buscador de platillos -->
                    <div class="mb-6">
                        <div class="relative">
                            <input type="text" id="dishSearchInput" placeholder="Buscar platillos..." 
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de platillos -->
                    <div id="dish-list" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto pr-2">
                        @foreach($dishes as $dish)
                            @php
                                $availability = $dishesAvailability[$dish->id] ?? ['available' => true, 'message' => 'Disponible'];
                            @endphp
                            <div class="dish-card {{ $availability['available'] ? 'available' : 'unavailable' }} bg-gray-50 rounded-xl border-2 {{ $availability['available'] ? 'border-gray-200 hover:border-blue-400' : 'border-red-200' }} p-4 transition-all duration-200 cursor-pointer"
                                 data-dish-id="{{ $dish->id }}"
                                 data-search="{{ strtolower($dish->name) }}">
                                <div class="flex items-start space-x-3">
                                    <div class="flex items-center h-full pt-1">
                                        <input 
                                            type="checkbox" 
                                            id="dish_{{ $dish->id }}" 
                                            name="dishes[]" 
                                            value="{{ $dish->id }}" 
                                            class="dish-checkbox w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer"
                                            {{ !$availability['available'] ? 'disabled' : '' }}
                                        >
                                    </div>
                                    <div class="flex-1">
                                        <label for="dish_{{ $dish->id }}" class="cursor-pointer">
                                            <h4 class="font-bold text-gray-900 text-lg mb-1 {{ !$availability['available'] ? 'text-gray-400' : '' }}">
                                                {{ $dish->name }}
                                            </h4>
                                            @if($dish->description)
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($dish->description, 60) }}</p>
                                            @endif
                                            <div class="flex items-center justify-between">
                                                @if($availability['available'])
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Disponible
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $availability['message'] }}
                                                    </span>
                                                @endif
                                                @if($dish->price)
                                                    <span class="text-lg font-bold text-blue-600">${{ number_format($dish->price, 2) }}</span>
                                                @endif
                                            </div>
                                        </label>
                                        <div class="quantity-controls mt-3 hidden">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad:</label>
                                            <div class="flex items-center space-x-2">
                                                <button type="button" class="quantity-decrease bg-gray-200 hover:bg-gray-300 text-gray-700 w-10 h-10 rounded-lg font-bold text-xl transition">-</button>
                                                <input 
                                                    type="number" 
                                                    name="quantities[{{ $dish->id }}]" 
                                                    min="1" 
                                                    value="1" 
                                                    class="quantity-input w-20 text-center border-2 border-gray-300 rounded-lg px-3 py-2 font-bold text-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                    {{ !$availability['available'] ? 'disabled' : '' }}
                                                >
                                                <button type="button" class="quantity-increase bg-blue-500 hover:bg-blue-600 text-white w-10 h-10 rounded-lg font-bold text-xl transition">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="no-dishes-found" class="hidden text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-gray-500 font-medium">No se encontraron platillos</p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resumen y Envío -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Resumen de Orden
                    </h3>
                </div>

                <div class="p-6">
                    <div id="order-summary" class="space-y-3 mb-6 max-h-96 overflow-y-auto">
                        <div id="empty-order" class="text-center py-8">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-400 font-medium">Selecciona platillos para comenzar</p>
                        </div>
                    </div>

                    <div class="border-t-2 border-gray-200 pt-4 mb-6">
                        <div class="flex items-center justify-between text-lg font-bold">
                            <span class="text-gray-700">Total de items:</span>
                            <span id="total-items" class="text-blue-600 text-2xl">0</span>
                        </div>
                    </div>

                    <button type="submit" form="orderForm" id="submitOrderBtn" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" disabled>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Enviar a Cocina</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Órdenes Recientes -->
    <div class="mt-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-yellow-400 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Órdenes Recientes
                </h2>
            </div>

            <div id="recent-orders" class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($orders as $order)
                    <div class="order-recent-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-gray-200 p-5 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="bg-orange-500 text-white px-3 py-1 rounded-lg font-bold">
                                    #{{ $order->id }}
                                </div>
                                <span class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            @if(!$order->dishes->every(function($item) { return $item->completed; }))
                                <form action="{{ route('mesero.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas cancelar esta orden?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="space-y-2">
                            @foreach($order->dishes as $item)
                                <div class="flex items-start space-x-2 text-sm">
                                    <div class="flex-shrink-0 mt-0.5">
                                        @if($item->completed && $item->received)
                                            <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @elseif($item->completed)
                                            <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-5 h-5 bg-yellow-400 rounded-full animate-pulse"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">
                                            {{ $item->dish ? $item->dish->name : 'Platillo no encontrado' }}
                                            <span class="text-gray-600">(x{{ $item->quantity }})</span>
                                        </p>
                                        <p class="text-xs {{ $item->received ? 'text-blue-600' : ($item->completed ? 'text-green-600' : 'text-yellow-600') }}">
                                            {{ $item->received ? '✓ Entregado' : ($item->completed ? '✓ Listo' : '⏱ En preparación') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500 font-medium">No hay órdenes recientes</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
/* Animaciones */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Dish cards */
.dish-card.available:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.dish-card.unavailable {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Scrollbar */
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

/* Summary items */
.summary-item {
    animation: slideIn 0.2s ease-out;
}

/* Botones táctiles optimizados */
@media (hover: none) {
    button:active {
        transform: scale(0.95);
    }
}

/* Tablet optimization */
@media (min-width: 768px) and (max-width: 1024px) {
    .dish-card {
        font-size: 1.05rem;
    }
    
    button, input {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.dish-checkbox');
    const orderSummary = document.getElementById('order-summary');
    const emptyOrder = document.getElementById('empty-order');
    const totalItems = document.getElementById('total-items');
    const submitBtn = document.getElementById('submitOrderBtn');
    const searchInput = document.getElementById('dishSearchInput');
    const dishCards = document.querySelectorAll('.dish-card');
    const noDishesFound = document.getElementById('no-dishes-found');

    // Gestión de checkboxes y cantidades
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.dish-card');
            const quantityControls = card.querySelector('.quantity-controls');
            
            if (this.checked) {
                quantityControls.classList.remove('hidden');
                card.classList.add('ring-2', 'ring-blue-400', 'bg-blue-50');
            } else {
                quantityControls.classList.add('hidden');
                card.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50');
            }
            
            updateOrderSummary();
        });

        // Click en la card para seleccionar
        const card = checkbox.closest('.dish-card');
        card.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox' && 
                e.target.type !== 'number' && 
                !e.target.classList.contains('quantity-decrease') &&
                !e.target.classList.contains('quantity-increase') &&
                !checkbox.disabled) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });

    // Botones de cantidad
    document.querySelectorAll('.quantity-decrease').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const input = this.nextElementSibling;
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
                updateOrderSummary();
            }
        });
    });

    document.querySelectorAll('.quantity-increase').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const input = this.previousElementSibling;
            input.value = parseInt(input.value) + 1;
            updateOrderSummary();
        });
    });

    // Actualizar resumen al cambiar cantidad
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', updateOrderSummary);
    });

    // Buscador de platillos
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCount = 0;

        dishCards.forEach(card => {
            const searchData = card.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Mostrar/ocultar mensaje de no encontrados
        const dishList = document.getElementById('dish-list');
        if (visibleCount === 0 && searchTerm !== '') {
            dishList.classList.add('hidden');
            noDishesFound.classList.remove('hidden');
        } else {
            dishList.classList.remove('hidden');
            noDishesFound.classList.add('hidden');
        }
    });

    function updateOrderSummary() {
        const selectedDishes = [];
        let totalCount = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked && !checkbox.disabled) {
                const card = checkbox.closest('.dish-card');
                const dishId = card.getAttribute('data-dish-id');
                const dishName = card.querySelector('h4').textContent.trim();
                const quantityInput = card.querySelector('.quantity-input');
                const quantity = parseInt(quantityInput.value) || 1;
                const price = card.querySelector('.text-blue-600')?.textContent || '';

                selectedDishes.push({
                    id: dishId,
                    name: dishName,
                    quantity: quantity,
                    price: price
                });

                totalCount += quantity;
            }
        });

        // Actualizar resumen
        if (selectedDishes.length === 0) {
            emptyOrder.classList.remove('hidden');
            orderSummary.innerHTML = '';
            submitBtn.disabled = true;
        } else {
            emptyOrder.classList.add('hidden');
            
            orderSummary.innerHTML = selectedDishes.map(dish => `
                <div class="summary-item bg-blue-50 border-l-4 border-blue-500 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${dish.name}</h4>
                            ${dish.price ? `<p class="text-sm text-blue-600 font-medium">${dish.price}</p>` : ''}
                        </div>
                        <div class="bg-blue-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow">
                            ${dish.quantity}
                        </div>
                    </div>
                </div>
            `).join('');
            
            submitBtn.disabled = false;
        }

        // Actualizar total
        totalItems.textContent = totalCount;
    }

    // Validar formulario
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.dish-checkbox:checked');
        const selectedDishes = Array.from(checkedBoxes).filter(cb => !cb.disabled);
        
        if (selectedDishes.length === 0) {
            e.preventDefault();
            
            // Mostrar alerta personalizada
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-2xl z-50 animate-slideIn';
            alert.innerHTML = `
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Por favor selecciona al menos un platillo disponible</span>
                </div>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        } else {
            // Mostrar loading en el botón
            submitBtn.innerHTML = `
                <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Enviando...</span>
            `;
            submitBtn.disabled = true;
        }
    });

    // Inicializar
    updateOrderSummary();

    // Auto-actualizar órdenes recientes cada 15 segundos
    setInterval(() => {
        // Podrías implementar AJAX aquí para actualizar solo la sección de órdenes recientes
        // sin recargar toda la página
    }, 15000);
});
</script>
@endsection
