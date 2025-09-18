@extends('layouts.app')
@section('title','Platillos')
@section('content')
<a href="{{ route('dishes.create') }}">Crear platillo</a>
<table border="1" cellpadding="6">
<tr><th>Nombre</th><th>Disponible</th><th>Ingredientes</th><th>Acciones</th></tr>
@foreach($dishes as $d)
<tr>
  <td>{{ $d->name }}</td>
  <td>{{ $d->available ? 'Si':'No' }}</td>
  <td>
    @foreach($d->ingredients as $ing) {{ $ing->name }} ({{ $ing->pivot->quantity_required }} {{ $ing->unit }}), @endforeach
  </td>
  <td>
    <a href="{{ route('dishes.edit',$d->id) }}">Editar</a>
    <form action="{{ route('dishes.destroy',$d->id) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button>Borrar</button></form>
  </td>
</tr>
@endforeach
</table>
@endsection
