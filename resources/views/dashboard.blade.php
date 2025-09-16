@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Resumen general</h2>

    <ul>
        <li>Total de ingredientes: {{ $totalIngredients }}</li>
        <li>Ingredientes bajos en stock: {{ $lowStock }}</li>
        <li>Total de platillos: {{ $totalDishes }}</li>
        <li>Platillos disponibles: {{ $availableDishes }}</li>
        <li>Órdenes pendientes: {{ $pendingOrders }}</li>
    </ul>

    <h3>Ingredientes más críticos</h3>
    <table border="1" cellpadding="6">
        <tr><th>Ingrediente</th><th>Stock</th><th>Mínimo</th></tr>
        @foreach($lowStockItems as $item)
            <tr @if($item->stock < $item->min_stock) style="background:#ffe6e6" @endif>
                <td>{{ $item->name }}</td>
                <td>{{ $item->stock }}</td>
                <td>{{ $item->min_stock }}</td>
            </tr>
        @endforeach
    </table>
@endsection
