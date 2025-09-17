@extends('layouts.app')
@section('title','Orders')
@section('content')
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Proovedor</th><th>Fecha</th><th>Estado</th><th>Total</th></tr>
@foreach($orders as $o)
<tr>
  <td><a href="{{ url('/orders/'.$o->id) }}">{{ $o->id }}</a></td>
  <td>{{ $o->supplier->name ?? '—' }}</td>
  <td>{{ $o->date }}</td>
  <td>{{ $o->status }}</td>
  <td>{{ $o->total }}</td>
</tr>
@endforeach
</table>
@endsection
