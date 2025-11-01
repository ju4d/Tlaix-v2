@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="relative flex-1 max-w-md">
        <input type="text" id="searchInput" placeholder="Buscar usuarios..." 
               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg" style="outline:none;">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>
    <a href="{{ route('users.create') }}" class="bg-primary text-white px-4 py-2 rounded ml-4 inline-block">Registrar nuevo usuario</a>
</div>
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
        <tr class="user-row" data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . $user->role) }}">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const userRows = document.querySelectorAll('.user-row');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        userRows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Mostrar mensaje si no hay resultados
        const visibleRows = document.querySelectorAll('.user-row[style=""]').length;
        
        if (visibleRows === 0 && searchTerm !== '') {
            // Crear mensaje de no resultados si no existe
            const tbody = document.querySelector('tbody');
            const existingNoResults = document.getElementById('no-results');
            if (!existingNoResults) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.id = 'no-results';
                noResultsRow.innerHTML = `
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900 mb-2">No se encontraron resultados</h3>
                            <p class="text-sm text-gray-500">Intenta con otros términos de búsqueda.</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(noResultsRow);
            }
        } else {
            // Remover mensaje de no resultados si existe
            const noResultsRow = document.getElementById('no-results');
            if (noResultsRow) {
                noResultsRow.remove();
            }
        }
    });
});
</script>

@endsection
