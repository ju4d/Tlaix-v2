@extends('layouts.app')
@section('title','Dishes')
@section('content')
<a href="{{ route('dishes.create') }}">New Dish</a>
<table border="1" cellpadding="6">
<tr><th>Name</th><th>Available</th><th>Ingredients</th><th>Actions</th></tr>
@foreach($dishes as $d)
<tr>
  <td>{{ $d->name }}</td>
  <td>{{ $d->available ? 'Yes':'No' }}</td>
  <td>
    @foreach($d->ingredients as $ing) {{ $ing->name }} ({{ $ing->pivot->quantity_required }} {{ $ing->unit }}), @endforeach
  </td>
  <td>
    <a href="{{ route('dishes.edit',$d->id) }}">Edit</a>
    <form action="{{ route('dishes.destroy',$d->id) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
  </td>
</tr>
@endforeach
</table>
@endsection
