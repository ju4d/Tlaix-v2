<!-- resources/views/dishes/create.blade.php -->
@extends('layouts.app')
@section('title','Create Dish')
@section('content')
<form method="POST" action="{{ route('dishes.store') }}">
    @csrf
    <div class="card">
        <label>Nombre del platillo *</label>
        <input type="text" name="name" required value="{{ old('name') }}">

        <label>Descripcion</label>
        <textarea name="description" rows="3">{{ old('description') }}</textarea>

        <label>Precio *</label>
        <input type="number" name="price" step="0.01" required value="{{ old('price', 0) }}">

        <label>
            <input type="checkbox" name="available" value="1" {{ old('available', true) ? 'checked' : '' }}>
            Disponible
        </label>

        <h3>Ingredientes</h3>
        <div id="ingredients-section">
            @foreach($ingredients as $ingredient)
            <div style="margin: 10px 0; padding: 10px; border: 1px solid #ddd;">
                <label>
                    <input type="checkbox" class="ingredient-checkbox" data-id="{{ $ingredient->id }}">
                    {{ $ingredient->name }} ({{ $ingredient->stock }} {{ $ingredient->unit }} Disponible)
                </label>
                <input type="number"
                       name="ingredients[{{ $ingredient->id }}]"
                       placeholder="Cantidad requerida"
                       step="0.01"
                       min="0"
                       style="margin-left: 10px; width: 180px;"
                       disabled>
            </div>
            @endforeach
        </div>

        <button type="submit">Crear platillo</button>
        <a href="{{ route('dishes.index') }}" style="margin-left:10px">Cancelar</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.ingredient-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const input = document.querySelector(`input[name="ingredients[${this.dataset.id}]"]`);
            input.disabled = !this.checked;
            if (!this.checked) input.value = '';
        });
    });
});
</script>
@endsection
