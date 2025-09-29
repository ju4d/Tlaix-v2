<!-- resources/views/orders/show.blade.php -->
@extends('layouts.app')
@section('title','Detalles del Pedido')
@section('content')
<div class="card">
    <h2>Pedido #{{ $order->id }}</h2>
    <p><strong>Proveedor:</strong> {{ $order->supplier->name ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $order->date }}</p>
    <p><strong>Estado:</strong>
        <span style="color: {{ $order->status == 'received' ? 'green' : ($order->status == 'cancelled' ? 'red' : 'orange') }}">
            {{ ucfirst($order->status) }}
        </span>
    </p>
    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>

    <h3>Productos del pedido</h3>
    <table>
        <tr><th>Ingredientes</th><th>Cantidad</th><th>Costo unitario</th><th>Subtotal</th></tr>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->ingredient->name ?? 'N/A' }}</td>
            <td>{{ $item->quantity }} {{ $item->ingredient->unit ?? '' }}</td>
            <td>${{ number_format($item->unit_cost, 2) }}</td>
            <td>${{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </table>

    @if($order->status == 'pending')
        <form method="POST" action="{{ url('/orders/'.$order->id.'/receive') }}" style="margin-top: 20px;">
            @csrf
            <button type="submit" style="background: #27ae60;">Marcar como recibido</button>
        </form>
    @endif

    <a href="{{ route('orders.index') }}" style="margin-top: 15px; display: inline-block;">Volver a pedidos</a>
</div>
@endsection
