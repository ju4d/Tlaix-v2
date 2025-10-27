@extends('layouts.app')
@section('title', 'Registrar Usuario')
@section('content')
<form action="{{ route('users.store') }}" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    @csrf
    <div class="mb-4">
        <label for="name" class="block font-bold mb-2">Nombre</label>
        <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required value="{{ old('name') }}">
        @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
        <label for="email" class="block font-bold mb-2">Email</label>
        <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required value="{{ old('email') }}">
        @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
        <label for="role" class="block font-bold mb-2">Rol</label>
        <select name="role" id="role" class="w-full border rounded px-3 py-2" required>
            <option value="">Selecciona un rol</option>
            <option value="admin" @if(old('role')=='admin') selected @endif>Admin</option>
            <option value="chef" @if(old('role')=='chef') selected @endif>Chef</option>
            <option value="mesero" @if(old('role')=='mesero') selected @endif>Mesero</option>
        </select>
        @error('role')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
        <label for="password" class="block font-bold mb-2">Contraseña</label>
        <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
        @error('password')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div class="mb-4">
        <label for="password_confirmation" class="block font-bold mb-2">Confirmar Contraseña</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border rounded px-3 py-2" required>
    </div>
    <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Registrar</button>
    <a href="{{ route('users.index') }}" class="ml-4 text-gray-600 hover:underline">Cancelar</a>
</form>
@endsection
