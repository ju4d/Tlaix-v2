@extends('layouts.app')
@section('title','Agregar Ingrediente')
@section('content')

<div class="max-w-2xl">
    <form method="POST" action="{{ route('inventory.store') }}" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('Error')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="perecedero" {{ old('category') == 'perecedero' ? 'selected' : '' }}>Perecedero</option>
                        <option value="no_perecedero" {{ old('category') == 'no_perecedero' ? 'selected' : '' }}>No Perecedero</option>
                        <option value="bebida" {{ old('category') == 'bebida' ? 'selected' : '' }}>Bebida</option>
                        <option value="condimento" {{ old('category') == 'condimento' ? 'selected' : '' }}>Condimento</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de caducidad</label>
                    <input type="date" name="expiration_date" value="{{ old('expiration_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock actual *</label>
                    <input type="number" name="stock" step="0.01" required value="{{ old('stock', 0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock mínimo *</label>
                    <input type="number" name="min_stock" step="0.01" required value="{{ old('min_stock', 0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('min_stock') border-red-500 @enderror">
                    @error('min_stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unidad</label>
                    <select name="unit" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogramos</option>
                        <option value="lbs" {{ old('unit') == 'lbs' ? 'selected' : '' }}>Onzas</option>
                        <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Piezas</option>
                        <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>Litros</option>
                        <option value="bottles" {{ old('unit') == 'bottles' ? 'selected' : '' }}>Botellas</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor</label>
                    <select name="supplier_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Elegir Proveedor</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Costo por unidad</label>
                    <input type="number" name="cost" step="0.01" value="{{ old('cost', 0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                Agregar ingrediente
            </button>
        </div>
    </form>
</div>

@endsection
