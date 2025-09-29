@extends('layouts.app')
@section('title','Inventario')
@section('content')


<a href="{{ route('inventory.create') }}">Agregar ingrediente</a>
<table border="1" cellpadding="6">
    <tr><th>Nombre</th><th>Categoria</th><th>Stock</th><th>Min</th><th>Caducidad</th><th>Acciones</th></tr>
    @foreach($ingredients as $i)
    <tr @if($i->stock < $i->min_stock) style="background:#ffe6e6" @endif>
        <td>{{ $i->name }}</td>
        <td>{{ $i->category }}</td>
        <td>{{ $i->stock }} {{ $i->unit }}</td>
        <td>{{ $i->min_stock }}</td>
        <td>{{ $i->expiration_date }}</td>
        <td>
            <a href="{{ route('inventory.edit',$i->id) }}">Editar</a>
            <form action="{{ route('inventory.destroy',$i->id) }}" method="POST" style="display:inline">
                @csrf @method('DELETE') <button>Borrar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
