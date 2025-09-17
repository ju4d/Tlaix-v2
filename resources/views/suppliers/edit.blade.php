@extends('layouts.app')
@section('title','Editar Proveedor')
@section('content')
<form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
    @csrf @method('PUT')
    <div class="card">

        <label>Nombre empresa *</label>
        <input type="text" name="name" required value="{{ old('name', $supplier->name) }}">

        <label>Nombre</label>
        <input type="text" name="contact" value="{{ old('contact', $supplier->contact) }}">

        <label>Telefono</label>
        <input type="tel" name="phone" value="{{ old('phone', $supplier->phone) }}">

        <label>Correo electronico</label>
        <input type="email" name="email" value="{{ old('email', $supplier->email) }}">

        <div style="margin-top: 20px;">
            <button type="submit">Actualizar proovedor</button>
            <a href="{{ route('suppliers.index') }}" style="margin-left: 10px; color: #000000FF;">Cancelar</a>
        </div>
    </div>
</form>
@endsection
