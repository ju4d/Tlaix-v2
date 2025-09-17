@extends('layouts.app')
@section('title','Suppliers')
@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Supplier Management</h2>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">➕ Add New Supplier</a>
</div>

<div class="card">
    <table>
        <tr><th>Name</th><th>Contact Person</th><th>Phone</th><th>Email</th><th>Ingredients</th><th>Actions</th></tr>
        @forelse($suppliers as $supplier)
        <tr>
            <td><strong>{{ $supplier->name }}</strong></td>
            <td>{{ $supplier->contact ?? 'N/A' }}</td>
            <td>{{ $supplier->phone ?? 'N/A' }}</td>
            <td>{{ $supplier->email ?? 'N/A' }}</td>
            <td>
                <span style="background: #3498db; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.9em;">
                    {{ $supplier->ingredients->count() }} items
                </span>
            </td>
            <td>
                <a href="{{ route('suppliers.edit', $supplier->id) }}" style="color: #f39c12; margin-right: 10px;">✏️ Edit</a>
                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')"
                            style="background: none; border: none; color: #e74c3c; cursor: pointer;">
                        🗑️ Delete
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
