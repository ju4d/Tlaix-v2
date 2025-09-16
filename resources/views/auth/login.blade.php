@extends('layouts.app')
@section('title','Login')
@section('content')
<form method="POST" action="/login">
    @csrf
    <div><label>Correo</label><input type="email" name="email" required></div>
    <div><label>Contraseña</label><input type="password" name="password" required></div>
    <div><button type="submit">Ingresar</button></div>
    @if($errors->any()) <div style="color:red">{{$errors->first()}}</div> @endif
</form>
@endsection
