@extends('layouts.app')
@section('title','Editar Ingrediente')
@section('content')

<div class="max-w-2xl">
    <form method="POST" action="{{ route('inventory.update', $item->id) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Editar Ingrediente</h2>
                <div>
                    @if($item->stock < $item->min_stock)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Stock Bajo
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Stock Normal
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                    <input type="text" name="name" required value="{{ old('name', $item->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="perecedero" {{ old('category', $item->category) == 'perecedero' ? 'selected' : '' }}>Perecedero</option>
                        <option value="no_perecedero" {{ old('category', $item->category) == 'no_perecedero' ? 'selected' : '' }}>No Perecedero</option>
                        <option value="bebida" {{ old('category', $item->category) == 'bebida' ? 'selected' : '' }}>Bebida</option>
                        <option value="condimento" {{ old('category', $item->category) == 'condimento' ? 'selected' : '' }}>Condimento</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de caducidad</label>
                    <input type="date" name="expiration_date" value="{{ old('expiration_date', $item->expiration_date) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock actual *</label>
                    <div class="relative">
                        <input type="number" name="stock" step="0.01" required value="{{ old('stock', $item->stock) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror {{ $item->stock < $item->min_stock ? 'border-red-400' : '' }}">
                        @if($item->stock < $item->min_stock)
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($item->stock < $item->min_stock)
                        <p class="mt-1 text-sm text-red-600">¡Stock por debajo del mínimo!</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock mínimo *</label>
                    <input type="number" name="min_stock" step="0.01" required value="{{ old('min_stock', $item->min_stock) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('min_stock') border-red-500 @enderror">
                    @error('min_stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unidad</label>
                    <select name="unit" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="kg" {{ old('unit', $item->unit) == 'kg' ? 'selected' : '' }}>Kilogramos</option>
                        <option value="lbs" {{ old('unit', $item->unit) == 'lbs' ? 'selected' : '' }}>Onzas</option>
                        <option value="pcs" {{ old('unit', $item->unit) == 'pcs' ? 'selected' : '' }}>Piezas</option>
                        <option value="liters" {{ old('unit', $item->unit) == 'liters' ? 'selected' : '' }}>Litros</option>
                        <option value="bottles" {{ old('unit', $item->unit) == 'bottles' ? 'selected' : '' }}>Botellas</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor</label>
                    <select name="supplier_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccionar Proveedor</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $item->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Costo por unidad</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="cost" step="0.01" value="{{ old('cost', $item->cost) }}"
                               class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('inventory.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                Actualizar ingrediente
            </button>
        </div>
    </form>
</div>

@endsection
