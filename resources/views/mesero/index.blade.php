@extends('layouts.app')
@section('title', 'Mesero')
@section('content')
<h2 class="text-2xl font-bold mb-4">Generar nueva orden</h2>

@if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <h3 class="font-bold mb-2">Error al crear la orden:</h3>
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('mesero.orders.store') }}" method="POST" class="mb-8 bg-white p-4 rounded shadow" id="orderForm">
    @csrf
    <div class="mb-4">
        <label class="block font-bold mb-2">Selecciona platillos:</label>
        <div id="dish-list">
            @foreach($dishes as $dish)
                @php
                    $availability = $dishesAvailability[$dish->id] ?? ['available' => true, 'message' => 'Disponible'];
                @endphp
                <div class="flex items-center mb-3 p-3 rounded {{ !$availability['available'] ? 'bg-red-50 border border-red-200' : 'hover:bg-gray-50' }}">
                    <input 
                        type="checkbox" 
                        id="dish_{{ $dish->id }}" 
                        name="dishes[]" 
                        value="{{ $dish->id }}" 
                        class="dish-checkbox mr-2"
                        {{ !$availability['available'] ? 'disabled' : '' }}
                    >
                    <label for="dish_{{ $dish->id }}" class="mr-4 flex-1 cursor-pointer {{ !$availability['available'] ? 'text-gray-400' : '' }}">
                        <strong>{{ $dish->name }}</strong>
                        @if(!$availability['available'])
                            <span class="text-red-600 text-sm ml-2">({{ $availability['message'] }})</span>
                        @else
                            <span class="text-green-600 text-sm ml-2">(Disponible)</span>
                        @endif
                    </label>
                    <input 
                        type="number" 
                        name="quantities[{{ $dish->id }}]" 
                        min="1" 
                        value="1" 
                        class="quantity-input w-20 border rounded px-2 py-1" 
                        style="display:none;" 
                        placeholder="Cantidad"
                        {{ !$availability['available'] ? 'disabled' : '' }}
                    >
                </div>
            @endforeach
        </div>
    </div>
    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:opacity-80 transition">Enviar orden a cocina</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.dish-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            if (this.checked) {
                input.style.display = 'inline-block';
                input.required = true;
            } else {
                input.style.display = 'none';
                input.required = false;
            }
        });
    });
    
    // Validar antes de enviar
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.dish-checkbox:checked');
        const selectedDishes = Array.from(checkedBoxes).filter(cb => !cb.disabled);
        
        if (selectedDishes.length === 0) {
            e.preventDefault();
            alert('Por favor selecciona al menos un platillo disponible');
        }
    });
});
</script>

<h2 class="text-xl font-bold mb-4">Órdenes recientes</h2>
<div id="recent-orders">
    @foreach($orders as $order)
        <div class="bg-white rounded shadow p-4 mb-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold">Orden #{{ $order->id }}</h3>
                <div class="flex gap-2">
                    @if(!$order->dishes->every(function($item) { return $item->completed; }))
                    <form action="{{ route('mesero.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta orden?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Cancelar orden</button>
                    </form>
                    @endif
                </div>
            </div>
            <ul>
                @foreach($order->dishes as $item)
                    <li class="flex items-center justify-between gap-2">
                        <div>
                            @if($item->dish)
                                {{ $item->dish->name }} (x{{ $item->quantity }})
                            @else
                                <span class="text-red-600">Platillo no encontrado</span> (x{{ $item->quantity }})
                            @endif
                            - <span class="{{ $item->completed ? 'text-green-600' : 'text-yellow-600' }}">{{ $item->completed ? 'Completado' : 'En preparación' }}</span>
                        </div>
                        <div>
                            @if($item->received)
                                <span class="text-blue-600">Recibido</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
@endsection
