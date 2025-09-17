<!-- resources/views/inventory/create.blade.php -->
@extends('layouts.app')
@section('title','Add Ingredient')
@section('content')
<form method="POST" action="{{ route('inventory.store') }}">
    @csrf
    <div class="card">
        <label>Name *</label>
        <input type="text" name="name" required value="{{ old('name') }}">

        <label>Category</label>
        <select name="category">
            <option value="perecedero">Perecedero</option>
            <option value="no_perecedero">No Perecedero</option>
            <option value="bebida">Bebida</option>
            <option value="condimento">Condimento</option>
        </select>

        <label>Expiration Date</label>
        <input type="date" name="expiration_date" value="{{ old('expiration_date') }}">

        <label>Current Stock *</label>
        <input type="number" name="stock" step="0.01" required value="{{ old('stock', 0) }}">

        <label>Minimum Stock *</label>
        <input type="number" name="min_stock" step="0.01" required value="{{ old('min_stock', 0) }}">

        <label>Unit</label>
        <select name="unit">
            <option value="kg">Kilogram</option>
            <option value="lbs">Pounds</option>
            <option value="pcs">Pieces</option>
            <option value="liters">Liters</option>
            <option value="bottles">Bottles</option>
        </select>

        <label>Supplier</label>
        <select name="supplier_id">
            <option value="">Select Supplier</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>

        <label>Cost per Unit</label>
        <input type="number" name="cost" step="0.01" value="{{ old('cost', 0) }}">

        <button type="submit">Add Ingredient</button>
        <a href="{{ route('inventory.index') }}" style="margin-left:10px">Cancel</a>
    </div>
</form>
@endsection
