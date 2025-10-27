@extends('layouts.app')
@section('title', 'Mesero')
@section('content')
<h2 class="text-2xl font-bold mb-4">Generar nueva orden</h2>
<form action="{{ route('mesero.orders.store') }}" method="POST" class="mb-8 bg-white p-4 rounded shadow" id="orderForm">
    @csrf
    <div class="mb-4">
        <label class="block font-bold mb-2">Selecciona platillos:</label>
        <div id="dish-list">
            @foreach($dishes as $dish)
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="dish_{{ $dish->id }}" name="dishes[]" value="{{ $dish->id }}" class="dish-checkbox mr-2">
                    <label for="dish_{{ $dish->id }}" class="mr-4">{{ $dish->name }}</label>
                    <input type="number" name="quantities[{{ $dish->id }}]" min="1" value="1" class="quantity-input w-20 border rounded px-2 py-1" style="display:none;" placeholder="Cantidad">
                </div>
            @endforeach
        </div>
    </div>
    <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Enviar orden a cocina</button>
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
});
</script>
<h2 class="text-xl font-bold mb-4">Órdenes recientes</h2>
<div id="recent-orders">
    @foreach($orders as $order)
        <div class="bg-white rounded shadow p-4 mb-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold">Orden #{{ $order->id }}</h3>
                <div class="flex gap-2">
                    <form action="{{ route('mesero.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta orden?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Cancelar orden</button>
                    </form>
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
                            @if($item->completed && !$item->received)
                                <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded text-sm" disabled>Recibido</button>
                            @elseif($item->received)
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
