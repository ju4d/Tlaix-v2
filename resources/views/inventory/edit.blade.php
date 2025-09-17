<!-- resources/views/inventory/edit.blade.php -->
@extends('layouts.app')
@section('title','Edit Ingredient')
@section('content')
<form method="POST" action="{{ route('inventory.update', $item->id) }}">
    @csrf
    @method('PUT')
    <div class="card">
        <label>Name *</label>
        <input type="text" name="name" required value="{{ old('name', $item->name) }}">

        <label>Category</label>
        <select name="category">
            <option value="perecedero" {{ $item->category == 'perecedero' ? 'selected' : '' }}>Perecedero</option>
            <option value="no_perecedero" {{ $item->category == 'no_perecedero' ? 'selected' : '' }}>No Perecedero</option>
            <option value="bebida" {{ $item->category == 'bebida' ? 'selected' : '' }}>Bebida</option>
            <option value="condimento" {{ $item->category == 'condimento' ? 'selected' : '' }}>Condimento</option>
        </select>

        <label>Expiration Date</label>
        <input type="date" name="expiration_date" value="{{ old('expiration_date', $item->expiration_date) }}">

        <label>Current Stock *</label>
        <input type="number" name="stock" step="0.01" required value="{{ old('stock', $item->stock) }}">

        <label>Minimum Stock *</label>
        <input type="number" name="min_stock" step="0.01" required value="{{ old('min_stock', $item->min_stock) }}">

        <label>Unit</label>
        <select name="unit">
            <option value="kg" {{ $item->unit == 'kg' ? 'selected' : '' }}>Kilogram</option>
            <option value="lbs" {{ $item->unit == 'lbs' ? 'selected' : '' }}>Pounds</option>
            <option value="pcs" {{ $item->unit == 'pcs' ? 'selected' : '' }}>Pieces</option>
            <option value="liters" {{ $item->unit == 'liters' ? 'selected' : '' }}>Liters</option>
            <option value="bottles" {{ $item->unit == 'bottles' ? 'selected' : '' }}>Bottles</option>
        </select>

        <label>Supplier</label>
        <select name="supplier_id">
            <option value="">Select Supplier</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ $item->supplier_id == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>

        <label>Cost per Unit</label>
        <input type="number" name="cost" step="0.01" value="{{ old('cost', $item->cost) }}">

        <button type="submit">Update Ingredient</button>
        <a href="{{ route('inventory.index') }}" style="margin-left:10px">Cancel</a>
    </div>
</form>
@endsection
