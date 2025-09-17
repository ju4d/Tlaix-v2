<!-- resources/views/inventory/edit.blade.php -->
@extends('layouts.app')
@section('title','Edit Ingredient')
@section('content')
<form method="POST" action="{{ route('inventory.update', $item->id) }}">
    @csrf
    @method('PUT')
    <div class="card">
        <label>Nombre *</label>
        <input type="text" name="name" required value="{{ old('name', $item->name) }}">

        <label>Categoria</label>
        <select name="category">
            <option value="perecedero" {{ $item->category == 'perecedero' ? 'selected' : '' }}>Perecedero</option>
            <option value="no_perecedero" {{ $item->category == 'no_perecedero' ? 'selected' : '' }}>No Perecedero</option>
            <option value="bebida" {{ $item->category == 'bebida' ? 'selected' : '' }}>Bebida</option>
            <option value="condimento" {{ $item->category == 'condimento' ? 'selected' : '' }}>Condimento</option>
        </select>

        <label>Fecha de Caducidad</label>
        <input type="date" name="expiration_date" value="{{ old('expiration_date', $item->expiration_date) }}">

        <label>Stock actual *</label>
        <input type="number" name="stock" step="0.01" required value="{{ old('stock', $item->stock) }}">

        <label>Stock minimo *</label>
        <input type="number" name="min_stock" step="0.01" required value="{{ old('min_stock', $item->min_stock) }}">

        <label>Unidad</label>
        <select name="unit">
            <option value="kg" {{ $item->unit == 'kg' ? 'selected' : '' }}>Kilogramos</option>
            <option value="lbs" {{ $item->unit == 'lbs' ? 'selected' : '' }}>Onzas</option>
            <option value="pcs" {{ $item->unit == 'pcs' ? 'selected' : '' }}>Piezas</option>
            <option value="liters" {{ $item->unit == 'liters' ? 'selected' : '' }}>Litros</option>
            <option value="bottles" {{ $item->unit == 'bottles' ? 'selected' : '' }}>Botellas</option>
        </select>

        <label>Proveedor</label>
        <select name="supplier_id">
            <option value="">Seleccionar Proveedor</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ $item->supplier_id == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>

        <label>Costo por unidad</label>
        <input type="number" name="cost" step="0.01" value="{{ old('cost', $item->cost) }}">

        <button type="submit">Actualizar ingrediente</button>
        <a href="{{ route('inventory.index') }}" style="margin-left:10px">Cancelar</a>
    </div>
</form>
@endsection
