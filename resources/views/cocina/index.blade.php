@extends('layouts.app')
@section('title', 'Cocina')
@section('content')
<h2 class="text-2xl font-bold mb-4">Órdenes en preparación</h2>
<div id="orders-list">
    @foreach($orders as $order)
        <div class="bg-white rounded shadow p-4 mb-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold">Orden #{{ $order->id }}</h3>
                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta orden?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Eliminar orden</button>
                </form>
            </div>
            <ul class="mb-2">
                @foreach($order->dishes as $item)
                    <li class="flex items-center justify-between border-b py-1">
                        <span>{{ $item->dish->name }} (x{{ $item->quantity }})</span>
                        <form action="{{ route('cocina.complete', [$order->id, $item->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-sm">Marcar como hecho</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>

<h2 class="text-xl font-bold mt-10 mb-4">Histórico de platillos completados</h2>
<div id="historico-list">
    <div class="bg-white rounded shadow p-4">
        <ul>
            @forelse($historico as $item)
                <li class="flex items-center justify-between border-b py-1">
                    <span>
                        {{ $item->dish ? $item->dish->name : 'Platillo eliminado' }} (x{{ $item->quantity }})
                        <span class="text-gray-500 text-xs ml-2">Orden #{{ $item->order_id }} | {{ $item->updated_at->format('d/m/Y H:i') }}</span>
                    </span>
                </li>
            @empty
                <li class="text-gray-500">No hay platillos completados aún.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
