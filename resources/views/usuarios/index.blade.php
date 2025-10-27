@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<a href="{{ route('users.create') }}" class="bg-primary text-white px-4 py-2 rounded mb-4 inline-block">Registrar nuevo usuario</a>
@if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
@endif
<table class="min-w-full bg-white rounded shadow">
    <thead>
        <tr>
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">Nombre</th>
            <th class="py-2 px-4">Email</th>
            <th class="py-2 px-4">Rol</th>
            <th class="py-2 px-4">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td class="py-2 px-4">{{ $user->id }}</td>
            <td class="py-2 px-4">{{ $user->name }}</td>
            <td class="py-2 px-4">{{ $user->email }}</td>
            <td class="py-2 px-4">{{ $user->role }}</td>
            <td class="py-2 px-4">
                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline mr-2">Editar</a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
