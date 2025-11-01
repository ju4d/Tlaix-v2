@extends('layouts.app')
@section('title','Administrar Proveedores')
@section('content')

<div class="mb-6 flex justify-between items-center">
    <div class="relative flex-1 max-w-md">
        <input type="text" id="searchInput" placeholder="Buscar proveedores..." 
               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg " style="outline:none;">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>
    <a href="{{ route('suppliers.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center ml-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Agregar nuevo Proveedor
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($suppliers as $supplier)
            <tr class="hover:bg-gray-50 supplier-row" data-search="{{ strtolower($supplier->name . ' ' . ($supplier->contact ?? '') . ' ' . ($supplier->email ?? '') . ' ' . ($supplier->phone ?? '')) }}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $supplier->contact ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $supplier->phone ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $supplier->email ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $supplier->ingredients->count() }} productos
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-orange-600 hover:text-orange-900 transition duration-200">
                        Editar
                    </a>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este proveedor?')"
                                class="text-red-600 hover:text-red-900 transition duration-200 ml-2">
                            Borrar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">No hay proveedores</h3>
                        <p class="text-sm text-gray-500 mb-4">Comienza agregando tu primer proveedor.</p>
                        <a href="{{ route('suppliers.create') }}" class="text-orange-600 hover:text-orange-500">
                            Agregar proveedor
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const supplierRows = document.querySelectorAll('.supplier-row');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        supplierRows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Mostrar mensaje si no hay resultados
        const visibleRows = document.querySelectorAll('.supplier-row[style=""]').length;
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
