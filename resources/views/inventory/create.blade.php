<!-- resources/views/inventory/create.blade.php -->
@extends('layouts.app')
@section('title','Add Ingredient')
@section('content')
<form method="POST" action="{{ route('inventory.store') }}">
    @csrf
    <div class="card">
        <label>Nombre *</label>
        <input type="text" name="name" required value="{{ old('name') }}">

        <label>Categoria</label>
        <select name="category">
            <option value="perecedero">Perecedero</option>
            <option value="no_perecedero">No Perecedero</option>
            <option value="bebida">Bebida</option>
            <option value="condimento">Condimento</option>
        </select>

        <label>Fecha de caducidad</label>
        <input type="date" name="expiration_date" value="{{ old('expiration_date') }}">

        <label>Stock actual *</label>
        <input type="number" name="stock" step="0.01" required value="{{ old('stock', 0) }}">

        <label>Stock minimo *</label>
        <input type="number" name="min_stock" step="0.01" required value="{{ old('min_stock', 0) }}">

        <label>Unidad</label>
        <select name="unit">
            <option value="kg">Kilogramos</option>
            <option value="lbs">Onzas</option>
            <option value="pcs">Piezas</option>
            <option value="liters">Litros</option>
            <option value="bottles">Botellas</option>
        </select>

        <label>Proovedor</label>
        <select name="supplier_id">
            <option value="">Elegir Proovedor</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>

        <label>Costo por unidad</label>
        <input type="number" name="cost" step="0.01" value="{{ old('cost', 0) }}">

        <button type="submit">Agregar ingrediente</button>
        <a href="{{ route('inventory.index') }}" style="margin-left:10px">Cancelar</a>
    </div>
</form>
@endsection
