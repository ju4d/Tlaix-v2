@extends('layouts.app')
@section('title','Crear Proveedor')
@section('content')
<form method="POST" action="{{ route('suppliers.store') }}">
    @csrf
    <div class="card">
        <h3>Informacion del proovedor</h3>

        <label>Nombre empresa *</label>
        <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g., Fresh Foods Co.">

        <label>Nombre</label>
        <input type="text" name="contact" value="{{ old('contact') }}" placeholder="e.g., John Doe">

        <label>Telefono</label>
        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g., +52 33 1234 5678">

        <label>Correo electronico</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g., orders@company.com">

        <div style="margin-top: 20px;">
            <button type="submit">Crear proovedor</button>
            <a href="{{ route('suppliers.index') }}" style="margin-left: 10px; color: #000000FF;">Cancelar</a>
        </div>
    </div>
</form>
@endsection
