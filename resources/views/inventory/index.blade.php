@extends('layouts.app')
@section('title','Inventario')
@section('content')

<div class="mb-6 flex justify-between items-center">
    <div class="relative flex-1 max-w-md">
        <input type="text" id="searchInput" placeholder="Buscar ingredientes..." 
               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg" style="outline:none;">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>
    <a href="{{ route('inventory.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center ml-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Agregar ingrediente
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mínimo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caducidad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($ingredients as $i)
            <tr class="hover:bg-gray-50 {{ $i->stock < $i->min_stock ? 'bg-red-50 border-l-4 border-red-500' : '' }} ingredient-row" data-search="{{ strtolower($i->name . ' ' . ($i->category ?? '') . ' ' . $i->unit) }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ $i->name }}
                    @if($i->stock < $i->min_stock)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                            Stock Bajo
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $i->category }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span class="font-medium">{{ $i->stock }}</span> {{ $i->unit }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $i->min_stock }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $i->expiration_date ? \Carbon\Carbon::parse($i->expiration_date)->format('d/m/Y') : 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('inventory.edit',$i->id) }}" class="text-blue-600 hover:text-blue-900 transition duration-200">
                        Editar
                    </a>
                    <form action="{{ route('inventory.destroy',$i->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE') 
                        <button type="submit" class="text-red-600 hover:text-red-900 transition duration-200 ml-2" 
                                onclick="return confirm('¿Estás seguro de eliminar este ingrediente?')">
                            Borrar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($ingredients->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8a1 1 0 00-1-1H7a1 1 0 00-1 1v4a1 1 0 001 1h10a1 1 0 001-1V5z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay ingredientes</h3>
        <p class="mt-1 text-sm text-gray-500">Comienza agregando un nuevo ingrediente al inventario.</p>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const ingredientRows = document.querySelectorAll('.ingredient-row');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        ingredientRows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Mostrar mensaje si no hay resultados
        const visibleRows = document.querySelectorAll('.ingredient-row[style=""]').length;
        const emptyRow = document.querySelector('tr td[colspan="6"]');
        
        if (visibleRows === 0 && searchTerm !== '' && !emptyRow) {
            // Crear mensaje de no resultados si no existe
            const tbody = document.querySelector('tbody');
            const noResultsRow = document.createElement('tr');
            noResultsRow.id = 'no-results';
            noResultsRow.innerHTML = `
                <td colspan="6" class="px-6 py-12 text-center">
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
