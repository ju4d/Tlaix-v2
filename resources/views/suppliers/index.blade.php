@extends('layouts.app')
@section('title','Administrar Proveedores')
@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <a href="{{ route('suppliers.create') }}" style="color: #f39c12; margin-right: 10px;" class="btn btn-primary">Agregar nuevo Proveedor</a>
</div>

<div class="card">
    <table>
        <tr><th>Empresa</th><th>Nombre</th><th>Telefono</th><th>Correo electronico</th><th>Ingredientes</th><th>Acciones</th></tr>
        @forelse($suppliers as $supplier)
        <tr>
            <td><strong>{{ $supplier->name }}</strong></td>
            <td>{{ $supplier->contact ?? 'N/A' }}</td>
            <td>{{ $supplier->phone ?? 'N/A' }}</td>
            <td>{{ $supplier->email ?? 'N/A' }}</td>
            <td>
                <span style="background: #3498db; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.9em;">
                    {{ $supplier->ingredients->count() }} productos
                </span>
            </td>
            <td>
                <a href="{{ route('suppliers.edit', $supplier->id) }}" style="color: #f39c12; margin-right: 10px;">Editar</a>
                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Estas seguro?')"
                            style="background: rgb(239, 115, 115); border: none; color: #FFFFFFFF; cursor: pointer;">
                        Borrar
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; color: #7f8c8d; padding: 40px;">
                No suppliers found. <a href="{{ route('suppliers.create') }}">Add your first supplier</a>
            </td>
        </tr>
        @endforelse
    </table>
</div>
@endsection
