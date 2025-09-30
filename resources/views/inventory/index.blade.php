@extends('layouts.app')
@section('title','Inventario')
@section('content')

<div class="mb-6">
    <a href="{{ route('inventory.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
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
            <tr class="hover:bg-gray-50 {{ $i->stock < $i->min_stock ? 'bg-red-50 border-l-4 border-red-500' : '' }}">
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

@endsection
