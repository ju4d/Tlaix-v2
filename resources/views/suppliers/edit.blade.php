@extends('layouts.app')
@section('title','Editar Proveedor')
@section('content')

<div class="max-w-2xl">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Editar Proveedor</h2>
        <p class="mt-2 text-sm text-gray-600">Modifica la información del proveedor <span class="font-medium">{{ $supplier->name }}</span>.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}" class="space-y-6">
            @csrf @method('PUT')
            
            <!-- Company Information Section -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Empresa</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nombre de la Empresa *
                        </label>
                        <input type="text" 
                               id="name"
                               name="name" 
                               required 
                               value="{{ old('name', $supplier->name) }}" 
                               placeholder="ej. Fresh Foods Co."
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label for="contact" class="block text-sm font-medium text-gray-700">
                            Persona de Contacto
                        </label>
                        <input type="text" 
                               id="contact"
                               name="contact" 
                               value="{{ old('contact', $supplier->contact) }}" 
                               placeholder="ej. Juan Pérez"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('contact') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('contact')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Teléfono
                        </label>
                        <input type="tel" 
                               id="phone"
                               name="phone" 
                               value="{{ old('phone', $supplier->phone) }}" 
                               placeholder="ej. +52 33 1234 5678"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('phone') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Correo Electrónico
                        </label>
                        <input type="email" 
                               id="email"
                               name="email" 
                               value="{{ old('email', $supplier->email) }}" 
                               placeholder="ej. pedidos@empresa.com"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('suppliers.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar Proveedor
                </button>
            </div>
        </form>
    </div>

    <!-- Additional Actions -->
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Zona de Peligro</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>Eliminar este proveedor removerá todos los pedidos asociados. Esta acción no se puede deshacer.</p>
                </div>
                <div class="mt-3">
                    <form method="POST" action="{{ route('suppliers.destroy', $supplier->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('¿Estás seguro de que quieres eliminar este proveedor? Se eliminarán todos los pedidos asociados.')"
                                class="text-sm bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded-md transition duration-200">
                            Eliminar Proveedor
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
