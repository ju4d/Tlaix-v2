@extends('layouts.app')
@section('title','Inventory')
@section('content')

<!-- Formulario para agregar ingrediente -->
<form method="POST" action="{{ route('inventory.store') }}" class="mb-4">
    @csrf
    <div class="input-group mb-3">
        <span class="input-group-text" id="inputGroup-sizing-default">Nombre</span>
        <input 
            type="text" 
            name="name" 
            class="form-control" 
            aria-label="Nombre del ingrediente" 
            aria-describedby="inputGroup-sizing-default"
            required
        >
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text">Categoría</span>
        <input 
            type="text" 
            name="category" 
            class="form-control" 
            aria-label="Categoría"
            required
        >
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text">Stock</span>
        <input 
            type="number" 
            name="stock" 
            class="form-control" 
            aria-label="Stock"
            required
        >
        <span class="input-group-text">Unidad</span>
        <input 
            type="text" 
            name="unit" 
            class="form-control" 
            aria-label="Unidad"
            required
        >
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text">Stock mínimo</span>
        <input 
            type="number" 
            name="min_stock" 
            class="form-control" 
            aria-label="Stock mínimo"
            required
        >
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text">Fecha de expiración</span>
        <input 
            type="date" 
            name="expiration_date" 
            class="form-control" 
            aria-label="Fecha de expiración"
        >
    </div>
    <button class="btn btn-success" type="submit">Agregar ingrediente</button>
</form>

<a href="{{ route('inventory.create') }}">Add ingredient</a>
<table border="1" cellpadding="6">
    <tr><th>Name</th><th>Category</th><th>Stock</th><th>Min</th><th>Expiration</th><th>Actions</th></tr>
    @foreach($ingredients as $i)
    <tr @if($i->stock < $i->min_stock) style="background:#ffe6e6" @endif>
        <td>{{ $i->name }}</td>
        <td>{{ $i->category }}</td>
        <td>{{ $i->stock }} {{ $i->unit }}</td>
        <td>{{ $i->min_stock }}</td>
        <td>{{ $i->expiration_date }}</td>
        <td>
            <a href="{{ route('inventory.edit',$i->id) }}">Edit</a>
            <form action="{{ route('inventory.destroy',$i->id) }}" method="POST" style="display:inline">
                @csrf @method('DELETE') <button>Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
